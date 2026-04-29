<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Http\Controllers\Controller;
use App\Services\AccommodationPlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request, AccommodationPlanService $service): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images'      => ['nullable', 'array'],
            'images.*'    => ['image'],
            'prices'      => ['nullable', 'array'],
            'prices.*'    => ['nullable', 'integer', 'min:0'],
        ]);

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
