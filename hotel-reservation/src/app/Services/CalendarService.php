<?php

namespace App\Services;

use App\Enums\CalendarAvailability;
use App\Enums\ReservationSlotStatus;
use App\Models\AccommodationPlan;
use App\Models\ReservationSlot;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarService
{
    /**
     * プランの月次空室状況を返す。
     *
     * @return array<string, array{availability: CalendarAvailability, slot_id: int|null}>
     *         キーは 'Y-m-d' 形式の日付文字列
     */
    public function getMonthlyAvailability(AccommodationPlan $plan, int $year, int $month): array
    {
        $roomTypeIds = $plan->planRoomPrices()->pluck('room_type_id')->all();

        $firstDay = Carbon::create($year, $month, 1);
        $lastDay  = $firstDay->copy()->endOfMonth();

        $slots = ReservationSlot::whereIn('room_type_id', $roomTypeIds)
            ->whereBetween('start', [$firstDay->toDateString(), $lastDay->toDateString()])
            ->get()
            ->groupBy(fn ($slot) => $slot->start->toDateString());

        $calendar = [];
        foreach (CarbonPeriod::create($firstDay, $lastDay) as $day) {
            $dateKey    = $day->toDateString();
            $daySlots   = $slots->get($dateKey, collect());
            $availSlots = $daySlots->filter(fn ($s) => $s->status === ReservationSlotStatus::Available);
            $availCount = $availSlots->count();

            $availability = match (true) {
                $availCount >= 2 => CalendarAvailability::Available,
                $availCount === 1 => CalendarAvailability::Limited,
                default          => CalendarAvailability::Unavailable,
            };

            $calendar[$dateKey] = [
                'availability' => $availability,
                'slot_id'      => $availCount >= 1 ? $availSlots->first()->id : null,
            ];
        }

        return $calendar;
    }
}
