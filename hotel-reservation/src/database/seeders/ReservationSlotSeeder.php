<?php

namespace Database\Seeders;

use App\Enums\ReservationSlotStatus;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class ReservationSlotSeeder extends Seeder
{
    public function run(): void
    {
        $standard = RoomType::where('name', 'スタンダードルーム')->first();
        $deluxe   = RoomType::where('name', 'デラックスルーム')->first();
        $suite    = RoomType::where('name', 'スイートルーム')->first();

        // 2026年5〜7月分の予約枠を投入
        $startMonth = Carbon::create(2026, 5, 1);
        $endMonth   = Carbon::create(2026, 7, 31);
        $period     = CarbonPeriod::create($startMonth, $endMonth);

        foreach ($period as $day) {
            $dateStr = $day->toDateString();
            $endStr  = $day->copy()->addDay()->toDateString();

            // 土日は人気が高い → 満室に近い日を増やす
            $isWeekend = $day->isWeekend();
            $dayOfMonth = $day->day;

            // スタンダードルーム（定員3）
            $this->createSlots($standard, $dateStr, $endStr, $isWeekend, $dayOfMonth, maxCount: 3);

            // デラックスルーム（定員2）
            $this->createSlots($deluxe, $dateStr, $endStr, $isWeekend, $dayOfMonth, maxCount: 2);

            // スイートルーム（定員1）
            $this->createSlots($suite, $dateStr, $endStr, $isWeekend, $dayOfMonth, maxCount: 1);
        }
    }

    private function createSlots(
        ?RoomType $roomType,
        string $dateStr,
        string $endStr,
        bool $isWeekend,
        int $dayOfMonth,
        int $maxCount,
    ): void {
        if (! $roomType) {
            return;
        }

        // 月末付近（25日以降）や週末は埋まりやすい
        if ($isWeekend && $dayOfMonth >= 25) {
            // 満室：Bookedスロットを定員分
            for ($i = 0; $i < $maxCount; $i++) {
                ReservationSlot::create([
                    'room_type_id' => $roomType->id,
                    'start'        => $dateStr,
                    'end'          => $endStr,
                    'status'       => ReservationSlotStatus::Booked,
                ]);
            }
        } elseif ($isWeekend || $dayOfMonth >= 20) {
            // 残りわずか：Available 1 + Booked 残り
            ReservationSlot::create([
                'room_type_id' => $roomType->id,
                'start'        => $dateStr,
                'end'          => $endStr,
                'status'       => ReservationSlotStatus::Available,
            ]);
            for ($i = 1; $i < $maxCount; $i++) {
                ReservationSlot::create([
                    'room_type_id' => $roomType->id,
                    'start'        => $dateStr,
                    'end'          => $endStr,
                    'status'       => ReservationSlotStatus::Booked,
                ]);
            }
        } else {
            // 空室あり：Available を定員分
            for ($i = 0; $i < $maxCount; $i++) {
                ReservationSlot::create([
                    'room_type_id' => $roomType->id,
                    'start'        => $dateStr,
                    'end'          => $endStr,
                    'status'       => ReservationSlotStatus::Available,
                ]);
            }
        }
    }
}
