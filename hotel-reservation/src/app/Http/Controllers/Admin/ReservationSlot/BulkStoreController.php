<?php

namespace App\Http\Controllers\Admin\ReservationSlot;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Services\ReservationSlotService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BulkStoreController extends Controller
{
    public function __invoke(Request $request, ReservationSlotService $service): RedirectResponse
    {
        $validated = $request->validate([
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'from'         => ['required', 'date', 'before:to'],
            'to'           => ['required', 'date', 'after:from'],
        ]);

        $roomType = RoomType::findOrFail($validated['room_type_id']);
        $created  = $service->bulkStore(
            $roomType,
            Carbon::parse($validated['from']),
            Carbon::parse($validated['to'])
        );

        return redirect()->route('admin.reservation-slots.index')
            ->with('success', "{$created}件の予約枠を作成しました。");
    }
}
