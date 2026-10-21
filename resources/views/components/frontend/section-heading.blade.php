@props([
    'eyebrow' => null,
    'title',
    'align' => 'left',
])

@php
    $alignClass = $align === 'center' ? 'items-center text-center' : '';
@endphp

<div {{ $attributes->merge(['class' => 'flex max-w-[720px] flex-col gap-3 '.$alignClass]) }}>
    @if ($eyebrow)
        <span class="text-[15px] font-semibold tracking-[0.04em] text-accent-text">{{ $eyebrow }}</span>
    @endif

    <h2 class="m-0 text-[clamp(1.75rem,4vw,2.375rem)] leading-snug font-bold text-navy">{{ $title }}</h2>

    @if ($slot->isNotEmpty())
        <div class="text-ink-muted {{ $align === 'center' ? 'max-w-[620px]' : '' }}">
            {{ $slot }}
        </div>
    @endif
</div>
