<?php

namespace App\Http\Controllers\User\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Reservation\ReservationRequest;
use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\ReservationSlot;
use Illuminate\View\View;

class ConfirmController extends Controller
{
    public function __invoke(ReservationRequest $request): View
    {
        $validated = $request->validated();

        $slot  = ReservationSlot::findOrFail($validated['slot_id']);
        $plan  = AccommodationPlan::findOrFail($validated['plan_id']);
        $price = PlanRoomPrice::where('room_type_id', $slot->room_type_id)
            ->where('accommodation_plan_id', $plan->id)
            ->value('price');

        return view('user.reservation.confirm', compact('slot', 'plan', 'price', 'validated'));
    }
}
