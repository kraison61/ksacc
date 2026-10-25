<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('backend.services.index', [
            'services' => Service::query()->ordered()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('backend.services.form', [
            'service' => new Service([
                'icon' => 'book',
                'sort_order' => 0,
                'is_published' => true,
            ]),
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        Service::query()->create($request->serviceAttributes());

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'บันทึกบริการแล้ว');
    }

    public function edit(Service $service): View
    {
        return view('backend.services.form', [
            'service' => $service,
        ]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->serviceAttributes());

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('status', 'บันทึกการแก้ไขแล้ว');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'ลบบริการแล้ว');
    }
}
