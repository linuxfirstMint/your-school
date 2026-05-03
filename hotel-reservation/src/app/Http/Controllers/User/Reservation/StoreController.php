<?php

namespace App\Http\Controllers\User\Reservation;

use App\Http\Controllers\Controller;
use App\Mail\ReservationConfirmedMail;
use App\Mail\ReservationReceivedMail;
use App\Models\AccommodationPlan;
use App\Models\Admin;
use App\Models\ReservationSlot;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class StoreController extends Controller
{
    public function __invoke(Request $request, ReservationService $service): RedirectResponse
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

        $slot = ReservationSlot::findOrFail($validated['slot_id']);
        $plan = AccommodationPlan::findOrFail($validated['plan_id']);

        $guestData = array_diff_key($validated, ['slot_id' => '', 'plan_id' => '']);

        try {
            $reservation = $service->reserve($slot, $plan, $guestData);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['slot_id' => $e->getMessage()]);
        }

        Mail::send(new ReservationConfirmedMail($reservation));

        foreach (Admin::all() as $admin) {
            Mail::send(new ReservationReceivedMail($reservation, $admin->email));
        }

        return redirect()->route('user.reservations.complete');
    }
}
