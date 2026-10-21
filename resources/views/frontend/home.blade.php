<x-layouts.frontend :json-ld="$jsonLd">
    <x-frontend.hero />
    <x-frontend.stats />
    <x-frontend.services-grid :services="$services" />
    <x-frontend.pricing-grid :packages="$pricingPackages" :a-la-carte="$aLaCartePlans" />
    <x-frontend.why-us />
    <x-frontend.steps />
    <x-frontend.registration-band />
    <x-frontend.faq />
</x-layouts.frontend>
