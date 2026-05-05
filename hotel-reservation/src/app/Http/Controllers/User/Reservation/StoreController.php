<?php

namespace App\Http\Controllers\User\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Reservation\ReservationRequest;
use App\Models\AccommodationPlan;
use App\Models\ReservationSlot;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(ReservationRequest $request, ReservationService $service): RedirectResponse
    {
        $validated = $request->validated();

        $slot = ReservationSlot::findOrFail($validated['slot_id']);
        $plan = AccommodationPlan::findOrFail($validated['plan_id']);

        $guestData = array_diff_key($validated, ['slot_id' => '', 'plan_id' => '']);

        try {
            $service->reserve($slot, $plan, $guestData);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['slot_id' => $e->getMessage()]);
        }

        return redirect()->route('user.reservations.complete');
    }
}
