@props([
    'service',
])

<a
    href="{{ route('services.show', $service) }}"
    {{ $attributes->merge(['class' => 'flex flex-col gap-3 rounded-2xl border border-line bg-white p-7 text-inherit no-underline transition hover:border-navy/25 hover:shadow-[0_8px_24px_rgba(20,36,58,0.06)]']) }}
>
    <span class="flex size-12 items-center justify-center rounded-xl bg-navy-soft text-navy">
        <x-frontend.icon :name="$service->icon" :size="24" />
    </span>
    <h3 class="m-0 text-[21px] font-semibold text-ink">{{ $service->title }}</h3>
    <p class="m-0 text-ink-muted">{{ $service->description }}</p>
</a>
