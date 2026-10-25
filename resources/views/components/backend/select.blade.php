@props([
    'name',
    'options',
    'selected' => null,
    'placeholder' => null,
])

<select
    name="{{ $name }}"
    id="{{ $name }}"
    {{ $attributes->merge(['class' => 'w-full rounded-lg border border-line bg-white px-3 py-2 text-ink outline-none focus:border-navy']) }}
>
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" @selected((string) $selected === (string) $value)>{{ $label }}</option>
    @endforeach
</select>
