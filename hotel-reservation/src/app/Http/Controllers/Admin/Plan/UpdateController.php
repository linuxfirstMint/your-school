<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Plan\PlanRequest;
use App\Models\AccommodationPlan;
use App\Services\AccommodationPlanService;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    public function __invoke(PlanRequest $request, AccommodationPlan $plan, AccommodationPlanService $service): RedirectResponse
    {
        $validated = $request->validated();

        $prices = array_filter(
            $validated['prices'] ?? [],
            fn ($v) => $v !== null && $v !== '',
        );

        $service->update(
            $plan,
            $validated['name'],
            $validated['description'] ?? null,
            $validated['images'] ?? [],
            $prices,
        );

        return redirect()->route('admin.plans.index');
    }
}
