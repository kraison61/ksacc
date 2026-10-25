<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\LineRecipientSource;
use App\Models\LineRecipient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class LineWebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_signature_is_forbidden_and_stores_nothing(): void
    {
        config(['services.line.channel_secret' => 'test-secret']);

        $this->postWebhook([
            'events' => [
                $this->followEvent(),
            ],
        ], 'test-secret', 'not-a-signature')->assertForbidden();

        $this->assertDatabaseCount('line_recipients', 0);
    }

    public function test_webhook_is_forbidden_when_the_channel_secret_is_missing(): void
    {
        $this->postWebhook([
            'events' => [
                $this->followEvent(),
            ],
        ], '')->assertForbidden();

        $this->assertDatabaseCount('line_recipients', 0);
    }

    public function test_invalid_json_is_rejected(): void
    {
        config(['services.line.channel_secret' => 'test-secret']);

        $body = '{';
        $signature = base64_encode(hash_hmac('sha256', $body, 'test-secret', true));

        $this->call(
            'POST',
            route('line.webhook'),
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_LINE_SIGNATURE' => $signature,
            ],
            content: $body,
        )->assertBadRequest();

        $this->assertDatabaseCount('line_recipients', 0);
    }

    public function test_empty_events_are_accepted(): void
    {
        $this->postWebhook(['events' => []])->assertOk();

        $this->assertDatabaseCount('line_recipients', 0);
    }

    public function test_unknown_event_is_ignored(): void
    {
        $this->postWebhook([
            'events' => [
                [
                    'type' => 'beacon',
                    'source' => [
                        'type' => 'user',
                        'userId' => 'Uabc123',
                    ],
                ],
            ],
        ])->assertOk();

        $this->assertDatabaseCount('line_recipients', 0);
    }

    public function test_follow_ignores_a_recipient_id_with_unexpected_characters(): void
    {
        $event = $this->followEvent();
        $event['source']['userId'] = '<script>';

        $this->postWebhook(['events' => [$event]])->assertOk();

        $this->assertDatabaseCount('line_recipients', 0);
    }

    public function test_follow_stores_the_sender_and_replies_once(): void
    {
        $this->fakeLine();

        $this->postWebhook(['events' => [$this->followEvent()]])->assertOk();

        $recipient = LineRecipient::query()->first();

        $this->assertNotNull($recipient);
        $this->assertSame(LineRecipientSource::User, $recipient->source);
        $this->assertSame('Uabc123', $recipient->line_id);

        Http::assertSent(function (Request $request): bool {
            $text = $request['messages'][0]['text'] ?? '';

            return $request->url() === 'https://api.line.me/v2/bot/message/reply'
                && $request->hasHeader('Authorization', 'Bearer test-token')
                && $request['replyToken'] === 'reply-token'
                && str_contains($text, 'คลิกโทรหรือแอด LINE');
        });

        $this->fakeLine();

        $this->postWebhook([
            'events' => [
                [
                    'type' => 'message',
                    'replyToken' => 'another-token',
                    'source' => [
                        'type' => 'user',
                        'userId' => 'Uabc123',
                    ],
                ],
            ],
        ])->assertOk();

        $this->assertDatabaseCount('line_recipients', 1);
        Http::assertNothingSent();
    }

    public function test_follow_stores_the_sender_without_calling_line_when_no_token_is_set(): void
    {
        Http::preventStrayRequests();
        config([
            'services.line.channel_secret' => 'test-secret',
            'services.line.channel_access_token' => null,
        ]);

        $this->postWebhook(['events' => [$this->followEvent()]])->assertOk();

        $this->assertDatabaseHas('line_recipients', [
            'line_id' => 'Uabc123',
        ]);
        Http::assertNothingSent();
    }

    public function test_unfollow_removes_the_sender(): void
    {
        LineRecipient::factory()->create([
            'line_id' => 'Uabc123',
        ]);

        $this->postWebhook([
            'events' => [
                [
                    'type' => 'unfollow',
                    'source' => [
                        'type' => 'user',
                        'userId' => 'Uabc123',
                    ],
                ],
            ],
        ])->assertOk();

        $this->assertDatabaseCount('line_recipients', 0);
    }

    public function test_join_stores_a_group_and_leave_removes_it(): void
    {
        $this->fakeLine();

        $this->postWebhook([
            'events' => [
                [
                    'type' => 'join',
                    'replyToken' => 'reply-token',
                    'source' => [
                        'type' => 'group',
                        'groupId' => 'Cgroup123',
                    ],
                ],
            ],
        ])->assertOk();

        $this->assertDatabaseHas('line_recipients', [
            'source' => LineRecipientSource::Group->value,
            'line_id' => 'Cgroup123',
        ]);

        $this->postWebhook([
            'events' => [
                [
                    'type' => 'leave',
                    'source' => [
                        'type' => 'group',
                        'groupId' => 'Cgroup123',
                    ],
                ],
            ],
        ])->assertOk();

        $this->assertDatabaseCount('line_recipients', 0);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function postWebhook(array $payload, string $secret = 'test-secret', ?string $signature = null): TestResponse
    {
        config(['services.line.channel_secret' => $secret]);

        $body = json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        $signature ??= base64_encode(hash_hmac('sha256', $body, $secret, true));

        return $this->call(
            'POST',
            route('line.webhook'),
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_LINE_SIGNATURE' => $signature,
            ],
            content: $body,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function followEvent(): array
    {
        return [
            'type' => 'follow',
            'replyToken' => 'reply-token',
            'source' => [
                'type' => 'user',
                'userId' => 'Uabc123',
            ],
        ];
    }

    private function fakeLine(): void
    {
        config(['services.line.channel_access_token' => 'test-token']);

        Http::preventStrayRequests();
        Http::fake([
            'https://api.line.me/*' => Http::response([], 200),
        ]);
    }
}
