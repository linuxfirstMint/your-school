<?php

namespace App\Services;

use App\Enums\ReservationSlotStatus;
use App\Enums\ReservationStatus;
use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    /** @param array<string, mixed> $guestData */
    public function reserve(ReservationSlot $slot, AccommodationPlan $plan, array $guestData): Reservation
    {
        if ($slot->status !== ReservationSlotStatus::Available) {
            throw new \RuntimeException('この予約枠はすでに埋まっています。');
        }

        $price = PlanRoomPrice::where('room_type_id', $slot->room_type_id)
            ->where('accommodation_plan_id', $plan->id)
            ->value('price');

        if ($price === null) {
            throw new \RuntimeException('この部屋タイプとプランの組み合わせの料金が設定されていません。');
        }

        return DB::transaction(function () use ($slot, $plan, $price, $guestData): Reservation {
            $slot->update(['status' => ReservationSlotStatus::Booked]);

            return Reservation::create(array_merge([
                'reservation_slot_id'   => $slot->id,
                'accommodation_plan_id' => $plan->id,
                'plan_name'             => $plan->name,
                'price'                 => $price,
            ], $guestData));
        });
    }

    public function cancel(Reservation $reservation): void
    {
        DB::transaction(function () use ($reservation): void {
            $reservation->update(['status' => ReservationStatus::Cancelled]);
            $reservation->reservationSlot()->update(['status' => ReservationSlotStatus::Available]);
        });
    }
}
