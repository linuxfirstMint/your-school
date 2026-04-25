<?php

namespace App\Http\Controllers\Admin\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;

class CancelController extends Controller
{
    public function __invoke(Reservation $reservation, ReservationService $service): RedirectResponse
    {
        $service->cancel($reservation);

        return redirect()->route('admin.reservations.index')->with('success', '予約をキャンセルしました。');
    }
}
