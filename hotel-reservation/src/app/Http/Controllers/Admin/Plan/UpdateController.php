<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use App\Services\AccommodationPlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, AccommodationPlan $plan, AccommodationPlanService $service): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images'      => ['nullable', 'array'],
            'images.*'    => ['image'],
            'prices'      => ['nullable', 'array'],
            'prices.*'    => ['integer', 'min:0'],
        ]);

        $service->update(
            $plan,
            $validated['name'],
            $validated['description'] ?? null,
            $validated['images'] ?? [],
            $validated['prices'] ?? [],
        );

        return redirect()->route('admin.plans.index');
    }
}
