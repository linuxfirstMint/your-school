<?php

namespace App\Http\Controllers\User\Reservation;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\ReservationSlot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfirmController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'slot_id'    => 'required|exists:reservation_slots,id',
            'plan_id'    => 'required|exists:accommodation_plans,id',
            'last_name'  => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'address'    => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
            'message'    => 'nullable|string',
        ]);

        $slot  = ReservationSlot::findOrFail($validated['slot_id']);
        $plan  = AccommodationPlan::findOrFail($validated['plan_id']);
        $price = PlanRoomPrice::where('room_type_id', $slot->room_type_id)
            ->where('accommodation_plan_id', $plan->id)
            ->value('price');

        return view('user.reservation.confirm', compact('slot', 'plan', 'price', 'validated'));
    }
}
