<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Support\JsonLdBuilder;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function show(Service $service, JsonLdBuilder $jsonLdBuilder): View
    {
        abort_unless($service->is_published, 404);

        return view('frontend.services.show', [
            'service' => $service,
            'jsonLd' => $jsonLdBuilder->serviceGraph($service),
        ]);
    }
}
