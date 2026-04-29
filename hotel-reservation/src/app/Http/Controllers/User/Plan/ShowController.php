<?php

namespace App\Http\Controllers\User\Plan;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use Illuminate\Contracts\View\View;

class ShowController extends Controller
{
    public function __invoke(AccommodationPlan $plan): View
    {
        $plan->load(['planImages', 'planRoomPrices.roomType']);

        return view('user.plan.show', compact('plan'));
    }
}
