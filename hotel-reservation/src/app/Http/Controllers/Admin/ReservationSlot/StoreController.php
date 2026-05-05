<?php

namespace App\Http\Controllers\Admin\ReservationSlot;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservationSlot\ReservationSlotRequest;
use App\Models\RoomType;
use App\Services\ReservationSlotService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(ReservationSlotRequest $request, ReservationSlotService $service): RedirectResponse
    {
        $validated = $request->validated();

        $roomType = RoomType::findOrFail($validated['room_type_id']);

        try {
            $service->store($roomType, Carbon::parse($validated['start']), Carbon::parse($validated['end']));
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.reservation-slots.index')->with('success', '予約枠を作成しました。');
    }
}
