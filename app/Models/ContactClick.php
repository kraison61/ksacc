<?php

namespace App\Models;

use App\Enums\ContactClickChannel;
use Carbon\CarbonInterface;
use Database\Factories\ContactClickFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'channel',
    'page_url',
    'ip_address',
    'user_agent',
    'line_notified_at',
])]
class ContactClick extends Model
{
    public const DISPLAY_TIMEZONE = 'Asia/Bangkok';

    public const NOTIFY_COOLDOWN_MINUTES = 2;

    /** @use HasFactory<ContactClickFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'channel' => ContactClickChannel::class,
            'line_notified_at' => 'datetime',
        ];
    }

    public static function startOfToday(): CarbonInterface
    {
        return now(self::DISPLAY_TIMEZONE)->startOfDay()->utc();
    }

    public function displayedAt(): string
    {
        return $this->created_at->timezone(self::DISPLAY_TIMEZONE)->format('d/m/Y H:i');
    }

    public function notifiedAt(): ?string
    {
        return $this->line_notified_at?->timezone(self::DISPLAY_TIMEZONE)->format('d/m/Y H:i');
    }

    public function pageLabel(): string
    {
        $path = parse_url($this->page_url, PHP_URL_PATH);
        $query = parse_url($this->page_url, PHP_URL_QUERY);
        $page = (is_string($path) && $path !== '') ? $path : $this->page_url;

        if (is_string($query) && $query !== '') {
            $page .= '?'.$query;
        }

        return $page;
    }

    public function notificationMessage(): string
    {
        return implode("\n", [
            'มีผู้เข้าชมคลิกจากเว็บไซต์',
            'ช่องทาง: '.$this->channel->label(),
            'หน้า: '.Str::limit($this->pageLabel(), 300, ''),
            'เวลา: '.$this->displayedAt(),
            'IP: '.$this->ip_address,
        ]);
    }

    public function wasNotifiedRecently(): bool
    {
        return self::query()
            ->where('channel', $this->channel)
            ->where('ip_address', $this->ip_address)
            ->whereNotNull('line_notified_at')
            ->where('line_notified_at', '>', now()->subMinutes(self::NOTIFY_COOLDOWN_MINUTES))
            ->whereKeyNot($this->getKey())
            ->exists();
    }
}
