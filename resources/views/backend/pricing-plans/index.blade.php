<x-layouts.backend heading="แพ็กเกจราคา">
    <x-slot:actions>
        <x-backend.button :href="route('admin.pricing-plans.create')">เพิ่มแพ็กเกจราคา</x-backend.button>
    </x-slot:actions>

    <nav class="flex flex-wrap gap-2" aria-label="กรองประเภท">
        <a
            href="{{ route('admin.pricing-plans.index') }}"
            @class([
                'rounded-full px-3 py-1.5 text-sm font-medium no-underline',
                'bg-navy text-white' => $currentType === '',
                'bg-white text-navy ring-1 ring-line hover:bg-navy-soft' => $currentType !== '',
            ])
        >ทั้งหมด</a>
        @foreach (\App\Models\PricingPlan::TYPES as $value => $label)
            <a
                href="{{ route('admin.pricing-plans.index', ['type' => $value]) }}"
                @class([
                    'rounded-full px-3 py-1.5 text-sm font-medium no-underline',
                    'bg-navy text-white' => $currentType === $value,
                    'bg-white text-navy ring-1 ring-line hover:bg-navy-soft' => $currentType !== $value,
                ])
            >{{ $label }}</a>
        @endforeach
    </nav>

    <div class="overflow-hidden rounded-2xl border border-line bg-white">
        @if ($plans->isEmpty())
            <p class="m-0 p-6 text-ink-muted">ยังไม่มีรายการในกลุ่มนี้</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[48rem] border-collapse text-left text-sm">
                    <thead class="border-b border-line bg-page text-ink-muted">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">ลำดับ</th>
                            <th scope="col" class="px-4 py-3 font-medium">ประเภท</th>
                            <th scope="col" class="px-4 py-3 font-medium">ชื่อ</th>
                            <th scope="col" class="px-4 py-3 font-medium">ราคา</th>
                            <th scope="col" class="px-4 py-3 font-medium">สถานะ</th>
                            <th scope="col" class="px-4 py-3 font-medium"><span class="sr-only">จัดการ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                            <tr class="border-b border-line last:border-b-0">
                                <td class="px-4 py-3 text-ink-muted">{{ $plan->sort_order }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ \App\Models\PricingPlan::TYPES[$plan->type] ?? $plan->type }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-medium text-navy">{{ $plan->title }}</span>
                                        @if ($plan->is_featured)
                                            <span class="rounded-full bg-accent px-2 py-0.5 text-xs font-bold text-ink">แนะนำ</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-ink">
                                    {{ trim(implode(' ', array_filter([$plan->price_prefix, $plan->price_amount, $plan->price_suffix]))) }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($plan->is_published)
                                        <span class="rounded-full bg-navy-soft px-2.5 py-1 text-xs font-semibold text-navy">เผยแพร่</span>
                                    @else
                                        <span class="rounded-full bg-accent-band px-2.5 py-1 text-xs font-semibold text-accent-band-text">ฉบับร่าง</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.pricing-plans.edit', $plan) }}" class="font-medium text-navy no-underline hover:text-navy-hover">แก้ไข</a>
                                        <form method="POST" action="{{ route('admin.pricing-plans.destroy', $plan) }}" onsubmit="return confirm('ลบแพ็กเกจราคานี้หรือไม่?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-700 hover:text-red-800">ลบ</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if ($plans->hasPages())
        <div>{{ $plans->links() }}</div>
    @endif
</x-layouts.backend>
