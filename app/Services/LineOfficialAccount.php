<?php

namespace App\Services;

use App\Enums\LineRecipientSource;
use App\Models\LineRecipient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LineOfficialAccount
{
    private const PUSH_URL = 'https://api.line.me/v2/bot/message/push';

    private const REPLY_URL = 'https://api.line.me/v2/bot/message/reply';

    private const CONFIRMATION = 'บันทึกแล้ว ระบบจะแจ้งที่แชทนี้เมื่อมีคนคลิกโทรหรือแอด LINE จากเว็บไซต์';

    public function signatureIsValid(Request $request): bool
    {
        $secret = (string) config('services.line.channel_secret');
        $signature = $request->header('X-Line-Signature');

        if ($secret === '' || ! is_string($signature) || $signature === '') {
            return false;
        }

        $expected = base64_encode(hash_hmac('sha256', $request->getContent(), $secret, true));

        return hash_equals($expected, $signature);
    }

    public function hasToken(): bool
    {
        return $this->token() !== '';
    }

    public function configuredRecipientId(): ?string
    {
        $recipientId = (string) config('services.line.recipient_id');

        if (preg_match('/^[A-Za-z0-9]{1,64}$/', $recipientId) !== 1) {
            return null;
        }

        return $recipientId;
    }

    /**
     * @return list<string>
     */
    public function recipients(): array
    {
        $recipientIds = LineRecipient::query()
            ->orderBy('id')
            ->pluck('line_id')
            ->all();

        $configuredRecipientId = $this->configuredRecipientId();

        if ($configuredRecipientId !== null && ! in_array($configuredRecipientId, $recipientIds, true)) {
            $recipientIds[] = $configuredRecipientId;
        }

        return $recipientIds;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function ingest(array $payload): void
    {
        $events = $payload['events'] ?? [];

        if (! is_array($events)) {
            return;
        }

        foreach (array_slice($events, 0, 20) as $event) {
            if (! is_array($event)) {
                continue;
            }

            $this->ingestEvent($event);
        }
    }

    public function push(string $text): bool
    {
        if (! $this->hasToken()) {
            return false;
        }

        $sent = false;

        foreach ($this->recipients() as $recipientId) {
            $delivered = $this->send(self::PUSH_URL, [
                'to' => $recipientId,
                'messages' => [
                    ['type' => 'text', 'text' => $text],
                ],
            ]);

            $sent = $sent || $delivered;
        }

        return $sent;
    }

    /**
     * @param  array<string, mixed>  $event
     */
    private function ingestEvent(array $event): void
    {
        $type = $event['type'] ?? null;
        $source = $event['source'] ?? null;

        if (! is_string($type) || ! is_array($source)) {
            return;
        }

        $recipient = $this->recipientFromSource($source);

        if ($recipient === null) {
            return;
        }

        if (in_array($type, ['unfollow', 'leave'], true)) {
            LineRecipient::query()->where('line_id', $recipient['id'])->delete();

            return;
        }

        if (! in_array($type, ['follow', 'message', 'join'], true)) {
            return;
        }

        $record = LineRecipient::query()->firstOrCreate(
            ['line_id' => $recipient['id']],
            ['source' => $recipient['source']],
        );

        $replyToken = $event['replyToken'] ?? null;

        if (! $record->wasRecentlyCreated || ! is_string($replyToken)) {
            return;
        }

        if (preg_match('/^[\x21-\x7E]{1,500}$/', $replyToken) !== 1) {
            return;
        }

        $this->send(self::REPLY_URL, [
            'replyToken' => $replyToken,
            'messages' => [
                ['type' => 'text', 'text' => self::CONFIRMATION],
            ],
        ]);
    }

    /**
     * @param  array<string, mixed>  $source
     * @return array{id: string, source: LineRecipientSource}|null
     */
    private function recipientFromSource(array $source): ?array
    {
        $type = $source['type'] ?? null;
        $recipientSource = is_string($type) ? LineRecipientSource::tryFrom($type) : null;
        $recipientId = match ($recipientSource) {
            LineRecipientSource::User => $source['userId'] ?? null,
            LineRecipientSource::Group => $source['groupId'] ?? null,
            default => null,
        };

        if (! $recipientSource instanceof LineRecipientSource || ! is_string($recipientId) || preg_match('/^[A-Za-z0-9]{1,64}$/', $recipientId) !== 1) {
            return null;
        }

        return [
            'id' => $recipientId,
            'source' => $recipientSource,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function send(string $endpoint, array $payload): bool
    {
        $token = $this->token();

        if ($token === '') {
            return false;
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->asJson()
                ->connectTimeout(3)
                ->timeout(8)
                ->post($endpoint, $payload);
        } catch (ConnectionException) {
            Log::warning('LINE OA request failed to connect.', [
                'endpoint' => $endpoint,
            ]);

            return false;
        }

        if ($response->successful()) {
            return true;
        }

        Log::warning('LINE OA request failed.', [
            'endpoint' => $endpoint,
            'status' => $response->status(),
        ]);

        return false;
    }

    private function token(): string
    {
        return (string) config('services.line.channel_access_token');
    }
}
