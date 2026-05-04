<?php

namespace Tests\Feature\Services;

use App\Enums\CalendarAvailability;
use App\Enums\ReservationSlotStatus;
use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\ReservationSlot;
use App\Services\CalendarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarServiceTest extends TestCase
{
    use RefreshDatabase;

    private CalendarService $service;

    private AccommodationPlan $plan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CalendarService();
        $this->plan    = AccommodationPlan::factory()->create();
    }

    public function test_月全体の日付がキーとして返る(): void
    {
        $result = $this->service->getMonthlyAvailability($this->plan, 2026, 5);

        $this->assertCount(31, $result);
        $this->assertArrayHasKey('2026-05-01', $result);
        $this->assertArrayHasKey('2026-05-31', $result);
    }

    public function test_空きが2つ以上の日付はAvailableになる(): void
    {
        $slot1 = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        $slot2 = ReservationSlot::factory()->create([
            'room_type_id' => $slot1->room_type_id,
            'start'        => $slot1->start,
            'end'          => $slot1->end,
            'status'       => ReservationSlotStatus::Available,
        ]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $this->plan->id,
            'room_type_id'          => $slot1->room_type_id,
        ]);

        $date   = $slot1->start->toDateString();
        $result = $this->service->getMonthlyAvailability(
            $this->plan,
            (int) $slot1->start->format('Y'),
            (int) $slot1->start->format('n'),
        );

        $this->assertSame(CalendarAvailability::Available, $result[$date]['availability']);
        $this->assertNotNull($result[$date]['slot_id']);
    }

    public function test_空きが1つの日付はLimitedになる(): void
    {
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $this->plan->id,
            'room_type_id'          => $slot->room_type_id,
        ]);

        $date   = $slot->start->toDateString();
        $result = $this->service->getMonthlyAvailability(
            $this->plan,
            (int) $slot->start->format('Y'),
            (int) $slot->start->format('n'),
        );

        $this->assertSame(CalendarAvailability::Limited, $result[$date]['availability']);
        $this->assertSame($slot->id, $result[$date]['slot_id']);
    }

    public function test_スロットがない日付はUnavailableになる(): void
    {
        $result = $this->service->getMonthlyAvailability($this->plan, 2026, 5);

        $this->assertSame(CalendarAvailability::Unavailable, $result['2026-05-15']['availability']);
        $this->assertNull($result['2026-05-15']['slot_id']);
    }

    public function test_全スロットが予約済みの日付はUnavailableになる(): void
    {
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Booked]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $this->plan->id,
            'room_type_id'          => $slot->room_type_id,
        ]);

        $date   = $slot->start->toDateString();
        $result = $this->service->getMonthlyAvailability(
            $this->plan,
            (int) $slot->start->format('Y'),
            (int) $slot->start->format('n'),
        );

        $this->assertSame(CalendarAvailability::Unavailable, $result[$date]['availability']);
        $this->assertNull($result[$date]['slot_id']);
    }

    public function test_料金設定のない部屋タイプのスロットは対象外になる(): void
    {
        // planRoomPrices に登録されていないスロット
        ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);

        $result = $this->service->getMonthlyAvailability($this->plan, 2026, 5);

        foreach ($result as $day) {
            $this->assertSame(CalendarAvailability::Unavailable, $day['availability']);
        }
    }

    public function test_部屋タイプIDを指定するとその部屋タイプのスロットのみ対象になる(): void
    {
        $slotA = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        $slotB = ReservationSlot::factory()->create([
            'start'  => $slotA->start,
            'end'    => $slotA->end,
            'status' => ReservationSlotStatus::Available,
        ]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $this->plan->id,
            'room_type_id'          => $slotA->room_type_id,
        ]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $this->plan->id,
            'room_type_id'          => $slotB->room_type_id,
        ]);

        $date = $slotA->start->toDateString();

        // A の部屋タイプだけ指定 → slotA の 1 件だけカウント → Limited
        $result = $this->service->getMonthlyAvailability(
            $this->plan,
            (int) $slotA->start->format('Y'),
            (int) $slotA->start->format('n'),
            $slotA->room_type_id,
        );

        $this->assertSame(CalendarAvailability::Limited, $result[$date]['availability']);
    }

    public function test_月の境界日が正しく含まれる(): void
    {
        $result = $this->service->getMonthlyAvailability($this->plan, 2026, 2);

        // 2026年2月は28日
        $this->assertCount(28, $result);
        $this->assertArrayHasKey('2026-02-01', $result);
        $this->assertArrayHasKey('2026-02-28', $result);
        $this->assertArrayNotHasKey('2026-02-29', $result);
    }
}
