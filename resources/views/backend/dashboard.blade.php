<x-layouts.backend heading="แดชบอร์ด">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <a href="{{ route('admin.services.index') }}" class="flex flex-col gap-2 rounded-2xl border border-line bg-white p-6 no-underline">
            <span class="text-sm text-ink-muted">บริการ</span>
            <span class="text-3xl font-bold text-navy">{{ $serviceCount }}</span>
            <span class="text-sm text-ink-muted">เผยแพร่ {{ $publishedServiceCount }} รายการ</span>
        </a>

        <a href="{{ route('admin.pricing-plans.index') }}" class="flex flex-col gap-2 rounded-2xl border border-line bg-white p-6 no-underline">
            <span class="text-sm text-ink-muted">แพ็กเกจราคา</span>
            <span class="text-3xl font-bold text-navy">{{ $planCount }}</span>
            <span class="text-sm text-ink-muted">เผยแพร่ {{ $publishedPlanCount }} รายการ</span>
        </a>

        <a href="{{ route('admin.contact-clicks.index') }}" class="flex flex-col gap-2 rounded-2xl border border-line bg-white p-6 no-underline">
            <span class="text-sm text-ink-muted">การคลิกวันนี้</span>
            <span class="text-3xl font-bold text-navy">{{ $todayPhoneClicks + $todayLineClicks }}</span>
            <span class="text-sm text-ink-muted">โทร {{ $todayPhoneClicks }} · แอด LINE {{ $todayLineClicks }}</span>
        </a>
    </div>
</x-layouts.backend>
