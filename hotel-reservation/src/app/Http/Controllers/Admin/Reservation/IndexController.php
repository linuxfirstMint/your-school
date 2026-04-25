<?php

namespace App\Http\Controllers\Admin\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $reservations = Reservation::with(['reservationSlot.roomType', 'accommodationPlan'])
            ->latest()
            ->paginate(20);

        return view('admin.reservation.index', compact('reservations'));
    }
}
