@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'submit',
])

@php
    $variants = [
        'primary' => 'inline-flex items-center justify-center rounded-lg bg-navy px-4 py-2 text-sm font-semibold text-white no-underline hover:bg-navy-hover',
        'secondary' => 'inline-flex items-center justify-center rounded-lg border border-line bg-white px-4 py-2 text-sm font-semibold text-navy no-underline hover:bg-navy-soft',
        'danger' => 'inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50',
    ];
    $class = $variants[$variant] ?? $variants['primary'];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
@endif
