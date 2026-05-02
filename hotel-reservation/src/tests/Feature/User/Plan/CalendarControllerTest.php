<?php

namespace Tests\Feature\User\Plan;

use App\Enums\ReservationSlotStatus;
use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_カレンダーページが表示される(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->get(route('user.plans.calendar', $plan))
            ->assertOk();
    }

    public function test_カレンダーに凡例が表示される(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->get(route('user.plans.calendar', $plan))
            ->assertOk()
            ->assertSee('空室あり')
            ->assertSee('残りわずか')
            ->assertSee('満室');
    }

    public function test_削除済みプランは404になる(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $plan->delete();

        $this->get(route('user.plans.calendar', $plan))
            ->assertNotFound();
    }

    public function test_空きが2つ以上の日付に○が表示される(): void
    {
        $roomType1 = RoomType::factory()->create();
        $roomType2 = RoomType::factory()->create();
        $plan      = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType1->id,
        ]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType2->id,
        ]);
        ReservationSlot::factory()->create([
            'room_type_id' => $roomType1->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Available,
        ]);
        ReservationSlot::factory()->create([
            'room_type_id' => $roomType2->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Available,
        ]);

        $this->get(route('user.plans.calendar', ['plan' => $plan, 'year' => 2026, 'month' => 6]))
            ->assertOk()
            ->assertSee('○');
    }

    public function test_空きが1つの日付に△が表示される(): void
    {
        $roomType = RoomType::factory()->create();
        $plan     = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType->id,
        ]);
        ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Available,
        ]);

        $this->get(route('user.plans.calendar', ['plan' => $plan, 'year' => 2026, 'month' => 6]))
            ->assertOk()
            ->assertSee('△');
    }

    public function test_スロットがない日付に×が表示される(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->get(route('user.plans.calendar', ['plan' => $plan, 'year' => 2026, 'month' => 6]))
            ->assertOk()
            ->assertSee('×');
    }

    public function test_予約済みスロットしかない日付に×が表示される(): void
    {
        $roomType = RoomType::factory()->create();
        $plan     = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType->id,
        ]);
        ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Booked,
        ]);

        $this->get(route('user.plans.calendar', ['plan' => $plan, 'year' => 2026, 'month' => 6]))
            ->assertOk()
            ->assertSee('×');
    }

    public function test_プランに料金設定のない部屋タイプのスロットは○にならない(): void
    {
        $roomType      = RoomType::factory()->create();
        $otherRoomType = RoomType::factory()->create();
        $plan          = AccommodationPlan::factory()->create();
        // plan には $otherRoomType の料金のみ設定
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $otherRoomType->id,
        ]);
        // スロットは $roomType（料金設定なし）
        ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Available,
        ]);

        $this->get(route('user.plans.calendar', ['plan' => $plan, 'year' => 2026, 'month' => 6]))
            ->assertOk()
            ->assertSee('×');
    }

    public function test_○の日付に予約フォームへのリンクがある(): void
    {
        $roomType1 = RoomType::factory()->create();
        $roomType2 = RoomType::factory()->create();
        $plan      = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType1->id,
        ]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType2->id,
        ]);
        $slot = ReservationSlot::factory()->create([
            'room_type_id' => $roomType1->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Available,
        ]);
        ReservationSlot::factory()->create([
            'room_type_id' => $roomType2->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Available,
        ]);

        $this->get(route('user.plans.calendar', ['plan' => $plan, 'year' => 2026, 'month' => 6]))
            ->assertOk()
            ->assertSee(route('user.reservations.create', ['slot_id' => $slot->id, 'plan_id' => $plan->id]));
    }

    public function test_△の日付に予約フォームへのリンクがある(): void
    {
        $roomType = RoomType::factory()->create();
        $plan     = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType->id,
        ]);
        $slot = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Available,
        ]);

        $this->get(route('user.plans.calendar', ['plan' => $plan, 'year' => 2026, 'month' => 6]))
            ->assertOk()
            ->assertSee(route('user.reservations.create', ['slot_id' => $slot->id, 'plan_id' => $plan->id]));
    }
}
