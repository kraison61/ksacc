@php
    $pageTitle = $service->title.' — '.config('company.name');
    $lineUrl = config('company.line_url') ?: route('home').'#contact';
    $tracksLine = filled(config('company.line_url'));
    $phone = config('company.phone');
    $phoneDisplay = config('company.phone_display') ?: $phone;
    $telHref = $phone ? 'tel:'.$phone : route('home').'#contact';
@endphp

<x-layouts.frontend :title="$pageTitle" :json-ld="$jsonLd">
    <section class="bg-navy text-white">
        <x-frontend.container class="flex flex-col gap-6 py-16 md:py-20">
            <nav aria-label="Breadcrumb" class="text-sm text-navy-mist">
                <ol class="m-0 flex list-none flex-wrap items-center gap-2 p-0">
                    <li>
                        <a href="{{ route('home') }}" class="text-navy-mist no-underline hover:text-white">{{ config('company.name') }}</a>
                    </li>
                    <li aria-hidden="true">/</li>
                    <li class="text-white">{{ $service->title }}</li>
                </ol>
            </nav>

            <div class="flex flex-col gap-5">
                <span class="flex size-14 items-center justify-center rounded-xl bg-white/10 text-accent">
                    <x-frontend.icon :name="$service->icon" :size="28" />
                </span>

                <h1 class="m-0 text-[clamp(1.875rem,4vw,2.75rem)] leading-tight font-bold">
                    {{ $service->title }}
                </h1>

                <p class="m-0 max-w-[640px] text-lg text-navy-mist">
                    {{ $service->description }}
                </p>

                <div class="flex flex-wrap gap-3 pt-2">
                    <x-frontend.button
                        :href="$lineUrl"
                        variant="primary"
                        icon="line"
                        :data-contact-track="$tracksLine ? 'line' : null"
                        :target="$tracksLine ? '_blank' : null"
                        :rel="$tracksLine ? 'noopener noreferrer' : null"
                    >
                        แอด LINE ปรึกษาฟรี
                    </x-frontend.button>
                    <x-frontend.button
                        :href="$telHref"
                        variant="secondary"
                        icon="phone"
                        :data-contact-track="filled($phone) ? 'phone' : null"
                    >
                        โทร {{ $phoneDisplay ?: 'สอบถาม' }}
                    </x-frontend.button>
                </div>
            </div>
        </x-frontend.container>
    </section>

    <section class="px-6 py-16">
        <x-frontend.container class="flex flex-col gap-6">
            <x-frontend.section-heading
                eyebrow="บริการ"
                :title="$service->title"
            >
                <p class="m-0">{{ $service->description }}</p>
            </x-frontend.section-heading>

            <p class="m-0 max-w-[640px] text-ink-muted">
                สนใจบริการนี้หรือต้องการคำปรึกษาเพิ่มเติม ติดต่อ{{ config('company.name') }}ได้ทาง LINE หรือโทรศัพท์
            </p>

            <div>
                <a href="{{ route('home') }}#services" class="text-base font-semibold text-navy no-underline hover:text-navy-hover">
                    ← ดูบริการทั้งหมด
                </a>
            </div>
        </x-frontend.container>
    </section>
</x-layouts.frontend>
