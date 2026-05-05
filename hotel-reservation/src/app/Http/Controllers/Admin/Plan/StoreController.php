<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Plan\PlanRequest;
use App\Services\AccommodationPlanService;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(PlanRequest $request, AccommodationPlanService $service): RedirectResponse
    {
        $validated = $request->validated();

        $prices = array_filter(
            $validated['prices'] ?? [],
            fn ($v) => $v !== null && $v !== '',
        );

        $service->store(
            $validated['name'],
            $validated['description'] ?? null,
            $validated['images'] ?? [],
            $prices,
        );

        return redirect()->route('admin.plans.index');
    }
}
