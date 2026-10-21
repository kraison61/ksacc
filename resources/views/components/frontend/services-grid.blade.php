@props([
    'services',
    'eyebrow' => 'บริการของเรา',
    'title' => 'ครอบคลุมงานบัญชีและภาษีทุกขั้น ตั้งแต่เปิดกิจการจนปิดงบประจำปี',
    'showHeading' => true,
])

<section id="services" {{ $attributes->merge(['class' => 'px-6 py-24']) }}>
    <x-frontend.container class="flex flex-col gap-12">
        @if ($showHeading)
            <x-frontend.section-heading :eyebrow="$eyebrow" :title="$title" />
        @endif

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($services as $service)
                <x-frontend.service-card :service="$service" />
            @endforeach
        </div>
    </x-frontend.container>
</section>
