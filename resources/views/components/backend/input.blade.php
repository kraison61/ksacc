@props([
    'name',
    'type' => 'text',
    'value' => '',
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ $value }}"
    {{ $attributes->merge(['class' => 'w-full rounded-lg border border-line bg-white px-3 py-2 text-ink outline-none focus:border-navy']) }}
>
