<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PricingPlanRequest;
use App\Models\PricingPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingPlanController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();

        if (! array_key_exists($type, PricingPlan::TYPES)) {
            $type = '';
        }

        return view('backend.pricing-plans.index', [
            'plans' => PricingPlan::query()
                ->when($type !== '', fn (Builder $query) => $query->where('type', $type))
                ->ordered()
                ->paginate(20)
                ->withQueryString(),
            'currentType' => $type,
        ]);
    }

    public function create(): View
    {
        return view('backend.pricing-plans.form', [
            'plan' => new PricingPlan([
                'type' => PricingPlan::TYPE_PACKAGE,
                'sort_order' => 0,
                'is_published' => true,
                'is_featured' => false,
            ]),
        ]);
    }

    public function store(PricingPlanRequest $request): RedirectResponse
    {
        PricingPlan::query()->create($request->planAttributes());

        return redirect()
            ->route('admin.pricing-plans.index')
            ->with('status', 'บันทึกแพ็กเกจราคาแล้ว');
    }

    public function edit(PricingPlan $pricingPlan): View
    {
        return view('backend.pricing-plans.form', [
            'plan' => $pricingPlan,
        ]);
    }

    public function update(PricingPlanRequest $request, PricingPlan $pricingPlan): RedirectResponse
    {
        $pricingPlan->update($request->planAttributes());

        return redirect()
            ->route('admin.pricing-plans.edit', $pricingPlan)
            ->with('status', 'บันทึกการแก้ไขแล้ว');
    }

    public function destroy(PricingPlan $pricingPlan): RedirectResponse
    {
        $pricingPlan->delete();

        return redirect()
            ->route('admin.pricing-plans.index')
            ->with('status', 'ลบแพ็กเกจราคาแล้ว');
    }
}
