<?php

namespace Tests\Feature\Services;

use App\Enums\ReservationSlotStatus;
use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use App\Services\ReservationSlotService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationSlotServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReservationSlotService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ReservationSlotService();
    }

    public function test_個別に予約枠を作成できる(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);
        $start = Carbon::parse('2026-06-01');
        $end   = Carbon::parse('2026-06-02');

        $slot = $this->service->store($roomType, $start, $end);

        $this->assertInstanceOf(ReservationSlot::class, $slot);
        $this->assertDatabaseHas('reservation_slots', [
            'room_type_id' => $roomType->id,
            'start'        => '2026-06-01',
            'end'          => '2026-06-02',
            'status'       => ReservationSlotStatus::Available->value,
        ]);
    }

    public function test_定員を超えた予約枠は作成できない(): void
    {
        $roomType = RoomType::factory()->create(['count' => 1]);
        $start = Carbon::parse('2026-06-01');
        $end   = Carbon::parse('2026-06-02');

        // count=1 なので1枠まで作れる
        $this->service->store($roomType, $start, $end);

        // 2枠目は定員超過で例外
        $this->expectException(\RuntimeException::class);
        $this->service->store($roomType, $start, $end);
    }

    public function test_定員内なら同じ日程に複数枠を作成できる(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);
        $start = Carbon::parse('2026-06-01');
        $end   = Carbon::parse('2026-06-02');

        $this->service->store($roomType, $start, $end);
        $this->service->store($roomType, $start, $end);
        $this->service->store($roomType, $start, $end);

        $this->assertDatabaseCount('reservation_slots', 3);
    }

    public function test_期間一括で予約枠を作成できる(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);
        $from = Carbon::parse('2026-07-01');
        $to   = Carbon::parse('2026-07-04'); // 3泊分 (7/1-2, 7/2-3, 7/3-4)

        $created = $this->service->bulkStore($roomType, $from, $to);

        $this->assertSame(3, $created);
        $this->assertDatabaseCount('reservation_slots', 3);
        $this->assertDatabaseHas('reservation_slots', ['start' => '2026-07-01', 'end' => '2026-07-02']);
        $this->assertDatabaseHas('reservation_slots', ['start' => '2026-07-02', 'end' => '2026-07-03']);
        $this->assertDatabaseHas('reservation_slots', ['start' => '2026-07-03', 'end' => '2026-07-04']);
    }

    public function test_一括作成で定員に達した日程はスキップされる(): void
    {
        $roomType = RoomType::factory()->create(['count' => 1]);
        $start = Carbon::parse('2026-07-01');
        $end   = Carbon::parse('2026-07-02');

        // 7/1の枠を先に埋める
        $this->service->store($roomType, $start, $end);

        // 7/1〜7/3で一括作成：7/1はスキップ、7/2のみ作成
        $created = $this->service->bulkStore($roomType, Carbon::parse('2026-07-01'), Carbon::parse('2026-07-03'));

        $this->assertSame(1, $created);
        $this->assertDatabaseCount('reservation_slots', 2);
    }

    public function test_予約枠を編集できる(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);
        $slot = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Available,
            'start'        => '2026-08-01',
            'end'          => '2026-08-02',
        ]);

        $this->service->update($slot, $roomType, Carbon::parse('2026-08-10'), Carbon::parse('2026-08-11'));

        $this->assertDatabaseHas('reservation_slots', [
            'id'    => $slot->id,
            'start' => '2026-08-10',
            'end'   => '2026-08-11',
        ]);
    }

    public function test_予約済みの枠は編集できない(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);
        $slot = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Booked,
        ]);

        $this->expectException(\RuntimeException::class);
        $this->service->update($slot, $roomType, Carbon::parse('2026-09-01'), Carbon::parse('2026-09-02'));
    }

    public function test_予約枠を削除できる(): void
    {
        $roomType = RoomType::factory()->create();
        $slot = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Available,
        ]);

        $this->service->destroy($slot);

        $this->assertDatabaseMissing('reservation_slots', ['id' => $slot->id]);
    }

    public function test_予約が入っている枠は削除できない(): void
    {
        $roomType = RoomType::factory()->create();
        $plan = AccommodationPlan::factory()->create();
        $slot = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Booked,
        ]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType->id,
        ]);
        Reservation::factory()->create([
            'reservation_slot_id'   => $slot->id,
            'accommodation_plan_id' => $plan->id,
        ]);

        $this->expectException(\RuntimeException::class);
        $this->service->destroy($slot);
    }
}
