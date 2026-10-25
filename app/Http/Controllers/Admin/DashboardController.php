<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContactClickChannel;
use App\Http\Controllers\Controller;
use App\Models\ContactClick;
use App\Models\PricingPlan;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('backend.dashboard', [
            'serviceCount' => Service::query()->count(),
            'publishedServiceCount' => Service::query()->published()->count(),
            'planCount' => PricingPlan::query()->count(),
            'publishedPlanCount' => PricingPlan::query()->published()->count(),
            'todayPhoneClicks' => ContactClick::query()
                ->where('channel', ContactClickChannel::Phone)
                ->where('created_at', '>=', ContactClick::startOfToday())
                ->count(),
            'todayLineClicks' => ContactClick::query()
                ->where('channel', ContactClickChannel::Line)
                ->where('created_at', '>=', ContactClick::startOfToday())
                ->count(),
        ]);
    }
}
