<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use App\Services\AccommodationPlanService;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    public function __invoke(AccommodationPlan $plan, AccommodationPlanService $service): RedirectResponse
    {
        $service->destroy($plan);

        return redirect()->route('admin.plans.index');
    }
}
