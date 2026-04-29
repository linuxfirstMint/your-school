<?php

namespace App\Http\Controllers\Admin\ReservationSlot;

use App\Http\Controllers\Controller;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Illuminate\Contracts\View\View;

class EditController extends Controller
{
    public function __invoke(ReservationSlot $reservationSlot): View
    {
        $roomTypes = RoomType::orderBy('id')->get();

        return view('admin.reservation-slot.edit', compact('reservationSlot', 'roomTypes'));
    }
}
