@props([
    'name',
    'value' => '',
])

<textarea
    name="{{ $name }}"
    id="{{ $name }}"
    {{ $attributes->merge(['class' => 'min-h-32 w-full rounded-lg border border-line bg-white px-3 py-2 text-ink outline-none focus:border-navy']) }}
>{{ $value }}</textarea>
