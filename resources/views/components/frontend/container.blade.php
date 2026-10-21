@props([
    'as' => 'div',
])

<{{ $as }} {{ $attributes->merge(['class' => 'mx-auto max-w-[1200px] px-6']) }}>
    {{ $slot }}
</{{ $as }}>
