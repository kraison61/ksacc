<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ContactClickChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactClickRequest;
use App\Jobs\NotifyLineOfContactClick;
use App\Models\ContactClick;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ContactClickController extends Controller
{
    public function store(StoreContactClickRequest $request): Response
    {
        $userAgent = Str::limit(trim((string) $request->userAgent()), 1000, '');

        $click = ContactClick::query()->create([
            'channel' => ContactClickChannel::from($request->string('channel')->toString()),
            'page_url' => $request->string('page_url')->toString(),
            'ip_address' => $request->ip() ?: '0.0.0.0',
            'user_agent' => $userAgent === '' ? null : $userAgent,
        ]);

        NotifyLineOfContactClick::dispatchSync($click);

        return response()->noContent();
    }
}
