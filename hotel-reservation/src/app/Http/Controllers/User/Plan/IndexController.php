<?php

namespace App\Http\Controllers\User\Plan;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use Illuminate\Contracts\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $plans = AccommodationPlan::with(['planImages', 'planRoomPrices'])->paginate(12);

        return view('user.plan.index', compact('plans'));
    }
}
