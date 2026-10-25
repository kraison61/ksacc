@props([
    'href',
    'active' => false,
])

<a
    href="{{ $href }}"
    @if ($active) aria-current="page" @endif
    {{ $attributes->class([
        'rounded-lg px-3 py-2 text-sm font-medium no-underline',
        'bg-navy text-white' => $active,
        'text-navy hover:bg-navy-soft' => ! $active,
    ]) }}
>{{ $slot }}</a>
