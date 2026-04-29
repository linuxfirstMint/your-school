<?php

namespace App\Http\Controllers\Admin\ReservationSlot;

use App\Http\Controllers\Controller;
use App\Models\ReservationSlot;
use App\Services\ReservationSlotService;
use Illuminate\Http\RedirectResponse;

class DestroyController extends Controller
{
    public function __invoke(ReservationSlot $reservationSlot, ReservationSlotService $service): RedirectResponse
    {
        try {
            $service->destroy($reservationSlot);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.reservation-slots.index')->with('success', '予約枠を削除しました。');
    }
}
