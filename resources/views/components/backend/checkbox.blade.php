@props([
    'name',
    'label',
    'checked' => false,
])

<div class="flex flex-col gap-1.5">
    <label for="{{ $name }}" class="flex items-center gap-2 text-sm text-ink">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="1"
            @checked($checked)
            {{ $attributes->merge(['class' => 'size-4 rounded border-line text-navy']) }}
        >
        {{ $label }}
    </label>
    @error($name)
        <p class="m-0 text-sm text-red-700">{{ $message }}</p>
    @enderror
</div>
