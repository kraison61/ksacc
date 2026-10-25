<?php

namespace App\Jobs;

use App\Models\ContactClick;
use App\Services\LineOfficialAccount;
use Illuminate\Cache\LockTimeoutException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class NotifyLineOfContactClick implements ShouldQueue
{
    use Queueable;

    public int $timeout = 20;

    public function __construct(public ContactClick $contactClick) {}

    public function handle(LineOfficialAccount $line): void
    {
        $click = $this->contactClick->fresh();

        if ($click === null || $click->line_notified_at !== null) {
            return;
        }

        $lockKey = 'contact-click-line:'.$click->ip_address.':'.$click->channel->value;

        try {
            Cache::lock($lockKey, 10)->block(3, function () use ($click, $line): void {
                $click->refresh();

                if ($click->line_notified_at !== null || $click->wasNotifiedRecently()) {
                    return;
                }

                if (! $line->push($click->notificationMessage())) {
                    return;
                }

                $click->update([
                    'line_notified_at' => now(),
                ]);
            });
        } catch (LockTimeoutException) {
            return;
        }
    }
}
