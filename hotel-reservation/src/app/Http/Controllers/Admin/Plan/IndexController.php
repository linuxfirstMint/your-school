<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use App\Models\RoomType;
use Illuminate\Contracts\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $plans     = AccommodationPlan::with(['planImages', 'planRoomPrices.roomType'])->paginate(20);
        $roomTypes = RoomType::orderBy('id')->get();

        return view('admin.plan.index', compact('plans', 'roomTypes'));
    }
}
