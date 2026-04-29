<?php

namespace App\Services;

use App\Enums\ReservationSlotStatus;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Carbon\Carbon;

class ReservationSlotService
{
    public function store(RoomType $roomType, Carbon $start, Carbon $end): ReservationSlot
    {
        $this->assertCapacityAvailable($roomType, $start, $end);

        return ReservationSlot::create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Available,
            'start'        => $start->toDateString(),
            'end'          => $end->toDateString(),
        ]);
    }

    public function bulkStore(RoomType $roomType, Carbon $from, Carbon $to): int
    {
        $created = 0;
        $current = $from->copy();

        while ($current->lt($to)) {
            $start = $current->copy();
            $end   = $current->copy()->addDay();
            try {
                $this->store($roomType, $start, $end);
                $created++;
            } catch (\RuntimeException) {
                // 定員に達している日程はスキップ
            }
            $current->addDay();
        }

        return $created;
    }

    public function update(ReservationSlot $slot, RoomType $roomType, Carbon $start, Carbon $end): void
    {
        if ($slot->status === ReservationSlotStatus::Booked) {
            throw new \RuntimeException('予約済みの枠は編集できません。');
        }

        $existing = ReservationSlot::where('room_type_id', $roomType->id)
            ->where('start', $start->toDateString())
            ->where('end', $end->toDateString())
            ->where('id', '!=', $slot->id)
            ->count();

        if ($existing >= $roomType->count) {
            throw new \RuntimeException('この部屋タイプ・日程の予約枠は定員に達しています。');
        }

        $slot->update([
            'room_type_id' => $roomType->id,
            'start'        => $start->toDateString(),
            'end'          => $end->toDateString(),
        ]);
    }

    public function destroy(ReservationSlot $slot): void
    {
        if ($slot->reservation()->exists()) {
            throw new \RuntimeException('予約が入っている枠は削除できません。');
        }

        $slot->delete();
    }

    private function assertCapacityAvailable(RoomType $roomType, Carbon $start, Carbon $end): void
    {
        $existing = ReservationSlot::where('room_type_id', $roomType->id)
            ->where('start', $start->toDateString())
            ->where('end', $end->toDateString())
            ->count();

        if ($existing >= $roomType->count) {
            throw new \RuntimeException('この部屋タイプ・日程の予約枠は定員に達しています。');
        }
    }
}
