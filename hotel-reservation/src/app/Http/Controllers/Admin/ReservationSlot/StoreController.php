<?php

namespace App\Http\Controllers\Admin\ReservationSlot;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Services\ReservationSlotService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request, ReservationSlotService $service): RedirectResponse
    {
        $validated = $request->validate([
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'start'        => ['required', 'date', 'before:end'],
            'end'          => ['required', 'date', 'after:start'],
        ]);

        $roomType = RoomType::findOrFail($validated['room_type_id']);

        try {
            $service->store($roomType, Carbon::parse($validated['start']), Carbon::parse($validated['end']));
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.reservation-slots.index')->with('success', '予約枠を作成しました。');
    }
}
