<?php

namespace App\Http\Controllers\Admin\ReservationSlot;

use App\Http\Controllers\Controller;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request): View
    {
        $slots = ReservationSlot::with('roomType')
            ->orderBy('start')
            ->orderBy('room_type_id')
            ->paginate(20);

        $roomTypes = RoomType::orderBy('id')->get();

        return view('admin.reservation-slot.index', compact('slots', 'roomTypes'));
    }
}
