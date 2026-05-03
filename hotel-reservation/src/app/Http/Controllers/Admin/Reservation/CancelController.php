<?php

namespace App\Http\Controllers\Admin\Reservation;

use App\Http\Controllers\Controller;
use App\Mail\ReservationCancelledMail;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class CancelController extends Controller
{
    public function __invoke(Reservation $reservation, ReservationService $service): RedirectResponse
    {
        $service->cancel($reservation);

        Mail::send(new ReservationCancelledMail($reservation));

        return redirect()->route('admin.reservations.index')->with('success', '予約をキャンセルしました。');
    }
}
