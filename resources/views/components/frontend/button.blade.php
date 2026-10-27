@props([
    'href' => '#',
    'variant' => 'primary',
    'icon' => null,
    'iconSize' => 20,
])

@php
    $base = 'inline-flex items-center justify-center gap-2.5 rounded-xl no-underline transition-colors';

    $variants = [
        'primary' => 'bg-accent px-7 py-4 text-lg font-bold text-ink hover:bg-accent/90',
        'secondary' => 'border border-white/55 px-[26px] py-[15px] text-lg font-semibold text-white hover:bg-white/10',
        'call' => 'bg-call px-7 py-4 text-lg font-bold text-white hover:bg-call-hover',
        'outline' => 'border-[1.5px] border-navy px-5 py-3.5 text-center font-semibold text-navy hover:bg-navy-soft',
        'navy' => 'bg-navy px-6 py-4 text-center font-semibold text-white hover:bg-navy-hover',
        'pill' => 'min-h-6 gap-2 rounded-full bg-accent px-5 py-2.5 text-base font-semibold text-ink hover:bg-accent/90',
    ];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $base.' '.($variants[$variant] ?? $variants['primary'])]) }}>
    @if ($icon)
        <x-frontend.icon :name="$icon" :size="$iconSize" />
    @endif
    {{ $slot }}
</a>
