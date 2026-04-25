<?php

namespace App\Http\Controllers\User\Reservation;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\ReservationSlot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreateController extends Controller
{
    public function __invoke(Request $request): View
    {
        $slot = ReservationSlot::findOrFail($request->query('slot_id'));
        $plan = AccommodationPlan::findOrFail($request->query('plan_id'));

        $price = PlanRoomPrice::where('room_type_id', $slot->room_type_id)
            ->where('accommodation_plan_id', $plan->id)
            ->value('price');

        return view('user.reservation.create', compact('slot', 'plan', 'price'));
    }
}
