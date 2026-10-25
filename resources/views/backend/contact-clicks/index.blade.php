<x-layouts.backend heading="การคลิก">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="flex flex-col gap-2 rounded-2xl border border-line bg-white p-6">
            <span class="text-sm text-ink-muted">โทรวันนี้</span>
            <span class="text-3xl font-bold text-navy">{{ $phoneCountToday }}</span>
        </div>
        <div class="flex flex-col gap-2 rounded-2xl border border-line bg-white p-6">
            <span class="text-sm text-ink-muted">แอด LINE วันนี้</span>
            <span class="text-3xl font-bold text-navy">{{ $lineCountToday }}</span>
        </div>
    </div>

    <section class="flex flex-col gap-3 rounded-2xl border border-line bg-white p-6">
        <h2 class="m-0 text-base font-semibold text-navy">แจ้งเตือน LINE OA</h2>

        @if (! $lineTokenConfigured)
            <p class="m-0 text-sm text-ink-muted">ยังไม่ได้ตั้งค่า LINE OA ใส่ LINE_CHANNEL_ACCESS_TOKEN และ LINE_CHANNEL_SECRET ในไฟล์ .env</p>
        @elseif ($notificationTargetCount === 0)
            <p class="m-0 text-sm text-ink-muted">ตั้งค่าโทเค็นแล้ว แต่ยังไม่มีผู้รับแจ้งเตือน ใส่ LINE_RECIPIENT_ID หรือให้ผู้รับเพิ่มบอทเป็นเพื่อนแล้วทักข้อความหาบอท</p>
        @else
            <p class="m-0 text-sm text-navy">จะแจ้งเตือนผ่าน LINE OA ไปยัง {{ $notificationTargetCount }} แชท</p>
        @endif

        <p class="m-0 break-all text-sm text-ink-muted">Webhook: {{ route('line.webhook') }}</p>

        @if ($lineRecipients->isNotEmpty() || $configuredRecipientId)
            <ul class="m-0 flex list-none flex-col gap-1 p-0 text-sm text-navy">
                @foreach ($lineRecipients as $recipient)
                    <li>{{ $recipient->source->label() }} {{ $recipient->line_id }}</li>
                @endforeach
                @if ($configuredRecipientId && ! $lineRecipients->contains('line_id', $configuredRecipientId))
                    <li>กำหนดใน .env {{ $configuredRecipientId }}</li>
                @endif
            </ul>
        @endif
    </section>

    <div class="flex flex-wrap gap-2">
        <x-backend.nav-link :href="route('admin.contact-clicks.index')" :active="$channel === null">ทั้งหมด</x-backend.nav-link>
        <x-backend.nav-link :href="route('admin.contact-clicks.index', ['channel' => 'phone'])" :active="$channel?->value === 'phone'">โทร</x-backend.nav-link>
        <x-backend.nav-link :href="route('admin.contact-clicks.index', ['channel' => 'line'])" :active="$channel?->value === 'line'">แอด LINE</x-backend.nav-link>
    </div>

    <p class="m-0 text-sm text-ink-muted">ระบบไม่มีชื่อผู้เข้าชม รายการนี้คือหน้าที่คลิก เวลา และไอพีของเครือข่าย</p>

    <div class="overflow-hidden rounded-2xl border border-line bg-white">
        @if ($clicks->isEmpty())
            <p class="m-0 p-6 text-ink-muted">
                @if ($channel)
                    ไม่พบรายการในช่องทางนี้
                @else
                    ยังไม่มีการคลิกโทรหรือแอด LINE
                @endif
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[56rem] border-collapse text-left text-sm">
                    <thead class="border-b border-line bg-page text-ink-muted">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">เวลา</th>
                            <th scope="col" class="px-4 py-3 font-medium">ช่องทาง</th>
                            <th scope="col" class="px-4 py-3 font-medium">หน้า</th>
                            <th scope="col" class="px-4 py-3 font-medium">ไอพี</th>
                            <th scope="col" class="px-4 py-3 font-medium">อุปกรณ์</th>
                            <th scope="col" class="px-4 py-3 font-medium">แจ้ง LINE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clicks as $click)
                            <tr class="border-b border-line last:border-b-0">
                                <td class="px-4 py-3 whitespace-nowrap text-ink-muted">{{ $click->displayedAt() }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'rounded-full px-2.5 py-1 text-xs font-semibold',
                                        'bg-navy-soft text-navy' => $click->channel->value === 'phone',
                                        'bg-accent-band text-accent-band-text' => $click->channel->value === 'line',
                                    ])>{{ $click->channel->label() }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ $click->page_url }}" class="font-medium text-navy no-underline hover:text-navy-hover" target="_blank" rel="noopener noreferrer">{{ $click->pageLabel() }}</a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-ink-muted">{{ $click->ip_address }}</td>
                                <td class="max-w-xs truncate px-4 py-3 text-ink-muted" title="{{ $click->user_agent }}">{{ $click->user_agent ?: '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-ink-muted">{{ $click->notifiedAt() ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if ($clicks->hasPages())
        <div>{{ $clicks->links() }}</div>
    @endif
</x-layouts.backend>
