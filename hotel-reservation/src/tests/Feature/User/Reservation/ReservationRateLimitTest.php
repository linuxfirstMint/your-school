<?php

namespace Tests\Feature\User\Reservation;

use App\Enums\ReservationSlotStatus;
use App\Models\AccommodationPlan;
use App\Models\Admin;
use App\Models\PlanRoomPrice;
use App\Models\ReservationSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationRateLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_1分間に10回を超えて予約するとレート制限される(): void
    {
        Mail::fake();

        Admin::create([
            'last_name'  => '管理',
            'first_name' => '太郎',
            'email'      => 'admin@example.com',
            'password'   => Hash::make('password'),
        ]);

        $plan = AccommodationPlan::factory()->create();
        $baseSlot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);

        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $baseSlot->room_type_id,
            'price'                 => 10000,
        ]);

        for ($i = 0; $i < 10; $i++) {
            $slot = ($i === 0)
                ? $baseSlot
                : ReservationSlot::factory()->create([
                    'room_type_id' => $baseSlot->room_type_id,
                    'status'       => ReservationSlotStatus::Available,
                ]);

            $this->post(route('user.reservations.store'), $this->postData($slot->id, $plan->id))
                ->assertRedirect();
        }

        $this->post(route('user.reservations.store'), $this->postData(9999, 9999))
            ->assertStatus(429);
    }

    /** @return array<string, mixed> */
    private function postData(int $slotId, int $planId): array
    {
        return [
            'slot_id'    => $slotId,
            'plan_id'    => $planId,
            'last_name'  => '田中',
            'first_name' => '花子',
            'email'      => 'hanako@example.com',
            'address'    => '大阪府大阪市1-1-1',
            'phone'      => '0612345678',
            'message'    => null,
        ];
    }
}
