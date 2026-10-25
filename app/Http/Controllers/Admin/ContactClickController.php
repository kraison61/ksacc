<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContactClickChannel;
use App\Http\Controllers\Controller;
use App\Models\ContactClick;
use App\Models\LineRecipient;
use App\Services\LineOfficialAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactClickController extends Controller
{
    public function index(Request $request, LineOfficialAccount $line): View
    {
        $channel = $this->selectedChannel($request);

        return view('backend.contact-clicks.index', [
            'clicks' => ContactClick::query()
                ->when($channel, fn (Builder $query) => $query->where('channel', $channel))
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate(20)
                ->withQueryString(),
            'channel' => $channel,
            'phoneCountToday' => $this->countToday(ContactClickChannel::Phone),
            'lineCountToday' => $this->countToday(ContactClickChannel::Line),
            'lineTokenConfigured' => $line->hasToken(),
            'lineRecipients' => LineRecipient::query()->orderBy('id')->get(),
            'configuredRecipientId' => $line->configuredRecipientId(),
            'notificationTargetCount' => count($line->recipients()),
        ]);
    }

    private function selectedChannel(Request $request): ?ContactClickChannel
    {
        $channel = $request->query('channel');

        if (! is_string($channel)) {
            return null;
        }

        return ContactClickChannel::tryFrom($channel);
    }

    private function countToday(ContactClickChannel $channel): int
    {
        return ContactClick::query()
            ->where('channel', $channel)
            ->where('created_at', '>=', ContactClick::startOfToday())
            ->count();
    }
}
