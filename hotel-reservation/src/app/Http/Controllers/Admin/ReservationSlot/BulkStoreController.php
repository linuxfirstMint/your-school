<?php

namespace App\Http\Controllers\Admin\ReservationSlot;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservationSlot\BulkStoreReservationSlotRequest;
use App\Models\RoomType;
use App\Services\ReservationSlotService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class BulkStoreController extends Controller
{
    public function __invoke(BulkStoreReservationSlotRequest $request, ReservationSlotService $service): RedirectResponse
    {
        $validated = $request->validated();

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
