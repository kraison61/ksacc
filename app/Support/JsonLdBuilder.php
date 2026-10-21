<?php

namespace App\Support;

use App\Models\Service;
use Illuminate\Support\Collection;

class JsonLdBuilder
{
    /**
     * @return array<string, mixed>
     */
    public function organization(): array
    {
        $data = [
            '@type' => 'AccountingService',
            '@id' => $this->organizationId(),
            'name' => config('company.name'),
            'url' => url('/'),
            'description' => config('company.tagline'),
        ];

        $alternateName = config('company.name_en');
        if (filled($alternateName)) {
            $data['alternateName'] = $alternateName;
        }

        $telephone = config('company.phone');
        if (filled($telephone)) {
            $data['telephone'] = $telephone;
        }

        $email = config('company.email');
        if (filled($email)) {
            $data['email'] = $email;
        }

        $address = $this->postalAddress();
        if ($address !== null) {
            $data['address'] = $address;
        }

        $sameAs = $this->sameAs();
        if ($sameAs !== []) {
            $data['sameAs'] = $sameAs;
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function webSite(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => config('company.name'),
            'description' => config('company.tagline'),
            'publisher' => [
                '@id' => $this->organizationId(),
            ],
        ];
    }

    /**
     * @param  Collection<int, Service>  $services
     * @return array<string, mixed>
     */
    public function homeGraph(Collection $services): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                $this->organization(),
                $this->webSite(),
                $this->serviceItemList($services),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function serviceGraph(Service $service): array
    {
        $serviceUrl = route('services.show', $service);

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                $this->organization(),
                [
                    '@type' => 'Service',
                    '@id' => $serviceUrl.'#service',
                    'name' => $service->title,
                    'description' => $service->description,
                    'url' => $serviceUrl,
                    'provider' => [
                        '@id' => $this->organizationId(),
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => config('company.name'),
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => $service->title,
                            'item' => $serviceUrl,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  Collection<int, Service>  $services
     * @return array<string, mixed>
     */
    protected function serviceItemList(Collection $services): array
    {
        return [
            '@type' => 'ItemList',
            '@id' => url('/').'#services',
            'name' => 'บริการของเรา',
            'numberOfItems' => $services->count(),
            'itemListElement' => $services->values()->map(function (Service $service, int $index): array {
                $serviceUrl = route('services.show', $service);

                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'Service',
                        '@id' => $serviceUrl.'#service',
                        'name' => $service->title,
                        'description' => $service->description,
                        'url' => $serviceUrl,
                        'provider' => [
                            '@id' => $this->organizationId(),
                        ],
                    ],
                ];
            })->all(),
        ];
    }

    protected function organizationId(): string
    {
        return url('/').'#organization';
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function postalAddress(): ?array
    {
        $streetAddress = config('company.address');
        $addressLines = config('company.address_lines', []);

        if (blank($streetAddress) && $addressLines === []) {
            return null;
        }

        if (blank($streetAddress) && $addressLines !== []) {
            $streetAddress = implode(', ', $addressLines);
        }

        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $streetAddress,
            'addressCountry' => 'TH',
        ];
    }

    /**
     * @return list<string>
     */
    protected function sameAs(): array
    {
        return array_values(array_filter([
            config('company.facebook_url'),
            config('company.line_url'),
        ], filled(...)));
    }
}
