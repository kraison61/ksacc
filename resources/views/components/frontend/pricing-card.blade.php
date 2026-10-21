@props([
    'plan',
])

@php
    $featured = (bool) $plan->is_featured;
    $features = $plan->features ?? [];
    $lastFeatureIsNote = ! $featured && count($features) > 0 && str_contains((string) end($features), 'ไม่รวม');
@endphp

<article
    @class([
        'relative flex flex-col gap-5 rounded-[20px] p-8',
        'bg-navy text-white shadow-[0_20px_48px_rgba(30,58,95,0.28)]' => $featured,
        'border border-line bg-white' => ! $featured,
    ])
>
    @if ($featured)
        <span class="absolute -top-3.5 left-8 rounded-full bg-accent px-3.5 py-1 text-sm font-bold text-ink">แนะนำ</span>
    @endif

    <div class="flex flex-col gap-1">
        <h3 @class(['m-0 text-[22px] font-semibold', 'text-ink' => ! $featured])>{{ $plan->title }}</h3>
        @if ($plan->subtitle)
            <span @class(['text-[15px]', 'text-navy-mist' => $featured, 'text-ink-muted' => ! $featured])>
                {{ $plan->subtitle }}
            </span>
        @endif
    </div>

    <div class="flex flex-col">
        @if ($plan->price_prefix)
            <span @class(['text-[15px]', 'text-navy-mist' => $featured, 'text-ink-muted' => ! $featured])>
                {{ $plan->price_prefix }}
            </span>
        @endif
        <span class="flex items-baseline gap-2">
            <span @class([
                'text-[44px] leading-[1.15] font-bold',
                'text-accent' => $featured,
                'text-navy' => ! $featured,
            ])>{{ $plan->price_amount }}</span>
            @if ($plan->price_suffix)
                <span @class([$featured ? 'text-navy-mist' : 'text-ink-muted'])>{{ $plan->price_suffix }}</span>
            @endif
        </span>
    </div>

    @if (count($features) > 0)
        <ul class="m-0 flex flex-1 list-none flex-col gap-2.5 p-0">
            @foreach ($features as $index => $feature)
                @php
                    $isNote = $lastFeatureIsNote && $index === array_key_last($features);
                @endphp
                <li @class([
                    'flex items-start gap-2.5',
                    'text-[15px] text-ink-muted' => $isNote,
                ])>
                    <x-frontend.icon
                        :name="$isNote ? 'info' : 'check'"
                        :size="18"
                        @class([
                            'mt-1 shrink-0',
                            'text-accent stroke-[2.4]' => $featured && ! $isNote,
                            'text-navy stroke-[2.4]' => ! $featured && ! $isNote,
                            'text-ink-muted' => $isNote,
                        ])
                    />
                    {{ $feature }}
                </li>
            @endforeach
        </ul>
    @endif

    @if ($plan->cta_label)
        <x-frontend.button
            href="{{ route('home') }}#contact"
            :variant="$featured ? 'primary' : 'outline'"
            class="{{ $featured ? 'font-bold' : '' }}"
        >
            {{ $plan->cta_label }}
        </x-frontend.button>
    @endif
</article>
