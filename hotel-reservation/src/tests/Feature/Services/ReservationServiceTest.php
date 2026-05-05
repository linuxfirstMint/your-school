<?php

namespace Tests\Feature\Services;

use App\Enums\ReservationSlotStatus;
use App\Enums\ReservationStatus;
use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReservationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->service = new ReservationService();
    }

    /** @return array<string, mixed> */
    private function guestData(array $overrides = []): array
    {
        return array_merge([
            'last_name'  => '山田',
            'first_name' => '太郎',
            'email'      => 'taro@example.com',
            'address'    => '東京都新宿区1-1-1',
            'phone'      => '0312345678',
            'message'    => null,
        ], $overrides);
    }

    public function test_正常に予約が作成できる(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $slot->room_type_id,
            'price'                 => 10000,
        ]);

        $reservation = $this->service->reserve($slot, $plan, $this->guestData());

        $this->assertInstanceOf(Reservation::class, $reservation);
        $this->assertDatabaseHas('reservations', [
            'reservation_slot_id'  => $slot->id,
            'accommodation_plan_id' => $plan->id,
        ]);
    }

    public function test_予約確定時にplan_nameとpriceのスナップショットが保存される(): void
    {
        $plan = AccommodationPlan::factory()->create(['name' => 'スタンダードプラン']);
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $slot->room_type_id,
            'price'                 => 15000,
        ]);

        $reservation = $this->service->reserve($slot, $plan, $this->guestData());

        $this->assertSame('スタンダードプラン', $reservation->plan_name);
        $this->assertSame(15000, $reservation->price);
    }

    public function test_予約済みの枠には予約できない(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Booked]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $slot->room_type_id,
            'price'                 => 10000,
        ]);

        $this->expectException(\RuntimeException::class);

        $this->service->reserve($slot, $plan, $this->guestData());
    }

    public function test_予約確定時に予約枠のステータスが埋まっているに更新される(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $slot->room_type_id,
            'price'                 => 10000,
        ]);

        $this->service->reserve($slot, $plan, $this->guestData());

        $this->assertSame(ReservationSlotStatus::Booked, $slot->fresh()->status);
    }

    public function test_予約キャンセル時に予約枠が解放される(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Booked]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $slot->room_type_id,
            'price'                 => 10000,
        ]);
        $reservation = Reservation::factory()->create([
            'reservation_slot_id'   => $slot->id,
            'accommodation_plan_id' => $plan->id,
            'status'                => ReservationStatus::Confirmed,
        ]);

        $this->service->cancel($reservation);

        $this->assertSame(ReservationSlotStatus::Available, $slot->fresh()->status);
        $this->assertSame(ReservationStatus::Cancelled, $reservation->fresh()->status);
    }

    public function test_料金が設定されていない場合は予約できない(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        // PlanRoomPrice を作らない

        $this->expectException(\RuntimeException::class);

        $this->service->reserve($slot, $plan, $this->guestData());
    }
}
