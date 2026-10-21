@props([
    'packages',
    'aLaCarte' => collect(),
    'eyebrow' => 'อัตราค่าบริการ',
    'title' => 'ราคาชัดเจน เลือกตามขนาดกิจการ',
    'description' => 'เริ่มจากแพ็กเกจที่พอดีกับกิจการวันนี้ แล้วขยับขึ้นได้เมื่อธุรกิจโตขึ้น เราประเมินราคาให้ชัดเจนก่อนเริ่มงานทุกครั้ง',
    'showHeading' => true,
    'note' => '* ราคายังไม่รวมค่าธรรมเนียมราชการและค่าผู้สอบบัญชี — เราแจ้งค่าใช้จ่ายทั้งหมดให้ทราบก่อนเริ่มงาน',
])

<section id="pricing" {{ $attributes->merge(['class' => 'border-y border-line bg-white px-6 py-24']) }}>
    <x-frontend.container class="flex flex-col gap-12">
        @if ($showHeading)
            <x-frontend.section-heading
                :eyebrow="$eyebrow"
                :title="$title"
                align="center"
                class="mx-auto"
            >
                <p class="m-0">{{ $description }}</p>
            </x-frontend.section-heading>
        @endif

        <div class="grid grid-cols-1 items-stretch gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($packages as $plan)
                <x-frontend.pricing-card :plan="$plan" />
            @endforeach
        </div>

        @if ($aLaCarte->isNotEmpty())
            <div class="flex flex-col gap-4">
                <h3 class="m-0 text-xl font-semibold text-ink">บริการแยกรายการ</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($aLaCarte as $item)
                        <div class="flex flex-col gap-1 rounded-[14px] bg-page px-[22px] py-5">
                            <span class="font-semibold">{{ $item->title }}</span>
                            <span class="text-xl font-bold text-navy">
                                @if ($item->price_prefix)
                                    <span class="text-[15px] font-medium text-ink-muted">{{ $item->price_prefix }}</span>
                                @endif
                                {{ $item->price_amount }}
                                @if ($item->price_suffix)
                                    <span class="text-[15px] font-medium text-ink-muted">{{ $item->price_suffix }}</span>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
                @if ($note)
                    <p class="m-0 text-[15px] text-ink-muted">{{ $note }}</p>
                @endif
            </div>
        @endif
    </x-frontend.container>
</section>
