@props([
    'label',
    'name',
    'hint' => null,
])

<div class="flex flex-col gap-1.5">
    <label for="{{ $name }}" class="text-sm font-medium text-navy">{{ $label }}</label>
    {{ $slot }}
    @if ($hint)
        <p class="m-0 text-xs text-ink-muted">{{ $hint }}</p>
    @endif
    @foreach (collect($errors->get($name))->merge(collect($errors->get($name.'.*'))->flatten())->unique() as $message)
        <p class="m-0 text-sm text-red-700">{{ $message }}</p>
    @endforeach
</div>
