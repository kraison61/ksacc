<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use App\Models\Service;
use App\Support\JsonLdBuilder;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(JsonLdBuilder $jsonLdBuilder): View
    {
        $services = Service::query()
            ->published()
            ->ordered()
            ->get();

        $pricingPackages = PricingPlan::query()
            ->published()
            ->packages()
            ->ordered()
            ->get();

        $aLaCartePlans = PricingPlan::query()
            ->published()
            ->aLaCarte()
            ->ordered()
            ->get();

        return view('frontend.home', [
            'services' => $services,
            'pricingPackages' => $pricingPackages,
            'aLaCartePlans' => $aLaCartePlans,
            'jsonLd' => $jsonLdBuilder->homeGraph($services),
        ]);
    }
}
