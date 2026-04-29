<?php

namespace Tests\Feature\Admin\ReservationSlot;

use App\Enums\ReservationSlotStatus;
use App\Models\AccommodationPlan;
use App\Models\Admin;
use App\Models\PlanRoomPrice;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReservationSlotControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::create([
            'last_name'  => '山田',
            'first_name' => '太郎',
            'email'      => 'admin@example.com',
            'password'   => Hash::make('password'),
        ]);
    }

    // ----------------------------------------------------------------
    // 未認証チェック
    // ----------------------------------------------------------------

    public function test_未認証ユーザーは一覧にアクセスできない(): void
    {
        $this->get(route('admin.reservation-slots.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは作成できない(): void
    {
        $this->post(route('admin.reservation-slots.store'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは一括作成できない(): void
    {
        $this->post(route('admin.reservation-slots.bulk-store'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは編集フォームにアクセスできない(): void
    {
        $slot = ReservationSlot::factory()->create();

        $this->get(route('admin.reservation-slots.edit', $slot))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは更新できない(): void
    {
        $slot = ReservationSlot::factory()->create();

        $this->put(route('admin.reservation-slots.update', $slot))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは削除できない(): void
    {
        $slot = ReservationSlot::factory()->create();

        $this->delete(route('admin.reservation-slots.destroy', $slot))
            ->assertRedirect(route('admin.login'));
    }

    // ----------------------------------------------------------------
    // IndexController
    // ----------------------------------------------------------------

    public function test_一覧ページが表示される(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reservation-slots.index'))
            ->assertOk();
    }

    public function test_予約枠の一覧が表示される(): void
    {
        $slot = ReservationSlot::factory()->create(['start' => '2026-07-01', 'end' => '2026-07-02']);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reservation-slots.index'))
            ->assertOk()
            ->assertSee('2026/07/01');
    }

    // ----------------------------------------------------------------
    // StoreController
    // ----------------------------------------------------------------

    public function test_正常な入力で予約枠を作成できる(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.reservation-slots.store'), [
                'room_type_id' => $roomType->id,
                'start'        => '2026-08-01',
                'end'          => '2026-08-02',
            ])
            ->assertRedirect(route('admin.reservation-slots.index'));

        $this->assertDatabaseHas('reservation_slots', [
            'room_type_id' => $roomType->id,
            'start'        => '2026-08-01',
            'end'          => '2026-08-02',
        ]);
    }

    public function test_バリデーションエラー時は予約枠が作成されない(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.reservation-slots.store'), [])
            ->assertSessionHasErrors(['room_type_id', 'start', 'end']);

        $this->assertDatabaseCount('reservation_slots', 0);
    }

    public function test_定員超過時はエラーメッセージが表示される(): void
    {
        $roomType = RoomType::factory()->create(['count' => 1]);
        ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'start'        => '2026-08-01',
            'end'          => '2026-08-02',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.reservation-slots.store'), [
                'room_type_id' => $roomType->id,
                'start'        => '2026-08-01',
                'end'          => '2026-08-02',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('reservation_slots', 1);
    }

    // ----------------------------------------------------------------
    // BulkStoreController
    // ----------------------------------------------------------------

    public function test_正常な入力で複数の予約枠を一括作成できる(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.reservation-slots.bulk-store'), [
                'room_type_id' => $roomType->id,
                'from'         => '2026-09-01',
                'to'           => '2026-09-04',
            ])
            ->assertRedirect(route('admin.reservation-slots.index'));

        $this->assertDatabaseCount('reservation_slots', 3);
    }

    public function test_一括作成でバリデーションエラー時は作成されない(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.reservation-slots.bulk-store'), [])
            ->assertSessionHasErrors(['room_type_id', 'from', 'to']);

        $this->assertDatabaseCount('reservation_slots', 0);
    }

    // ----------------------------------------------------------------
    // EditController
    // ----------------------------------------------------------------

    public function test_編集フォームが表示される(): void
    {
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reservation-slots.edit', $slot))
            ->assertOk();
    }

    // ----------------------------------------------------------------
    // UpdateController
    // ----------------------------------------------------------------

    public function test_正常な入力で予約枠を更新できる(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);
        $slot = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Available,
            'start'        => '2026-10-01',
            'end'          => '2026-10-02',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.reservation-slots.update', $slot), [
                'room_type_id' => $roomType->id,
                'start'        => '2026-10-10',
                'end'          => '2026-10-11',
            ])
            ->assertRedirect(route('admin.reservation-slots.index'));

        $this->assertDatabaseHas('reservation_slots', [
            'id'    => $slot->id,
            'start' => '2026-10-10',
            'end'   => '2026-10-11',
        ]);
    }

    public function test_予約済みの枠は更新できない(): void
    {
        $roomType = RoomType::factory()->create(['count' => 3]);
        $slot = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Booked,
            'start'        => '2026-10-01',
            'end'          => '2026-10-02',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.reservation-slots.update', $slot), [
                'room_type_id' => $roomType->id,
                'start'        => '2026-10-10',
                'end'          => '2026-10-11',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseHas('reservation_slots', [
            'id'    => $slot->id,
            'start' => '2026-10-01',
        ]);
    }

    // ----------------------------------------------------------------
    // DestroyController
    // ----------------------------------------------------------------

    public function test_予約枠を削除できる(): void
    {
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.reservation-slots.destroy', $slot))
            ->assertRedirect(route('admin.reservation-slots.index'));

        $this->assertDatabaseMissing('reservation_slots', ['id' => $slot->id]);
    }

    public function test_予約が入っている枠は削除できない(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $roomType = RoomType::factory()->create();
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

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.reservation-slots.destroy', $slot))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('reservation_slots', ['id' => $slot->id]);
    }
}
