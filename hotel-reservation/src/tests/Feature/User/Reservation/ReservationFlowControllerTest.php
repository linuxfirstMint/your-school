<?php

namespace Tests\Feature\User\Reservation;

use App\Enums\ReservationSlotStatus;
use App\Mail\ReservationConfirmedMail;
use App\Mail\ReservationReceivedMail;
use App\Models\AccommodationPlan;
use App\Models\Admin;
use App\Models\PlanRoomPrice;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationFlowControllerTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // CreateController
    // ----------------------------------------------------------------

    public function test_slot_idとplan_idを渡すと予約フォームが表示される(): void
    {
        $slot = ReservationSlot::factory()->create(['status' => ReservationSlotStatus::Available]);
        $plan = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'room_type_id'          => $slot->room_type_id,
            'accommodation_plan_id' => $plan->id,
        ]);

        $this->get(route('user.reservations.create', ['slot_id' => $slot->id, 'plan_id' => $plan->id]))
            ->assertOk();
    }

    public function test_存在しないslot_idを渡すと404になる(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->get(route('user.reservations.create', ['slot_id' => 9999, 'plan_id' => $plan->id]))
            ->assertNotFound();
    }

    // ----------------------------------------------------------------
    // ConfirmController
    // ----------------------------------------------------------------

    public function test_正常な入力で確認画面が表示される(): void
    {
        [$slot, $plan] = $this->makeAvailableSlotAndPlan();

        $this->post(route('user.reservations.confirm'), $this->postData($slot->id, $plan->id))
            ->assertOk();
    }

    public function test_バリデーションエラー時は確認画面に進めない(): void
    {
        $this->post(route('user.reservations.confirm'), [])
            ->assertSessionHasErrors(['slot_id', 'plan_id', 'last_name', 'first_name', 'email', 'address', 'phone']);
    }

    // ----------------------------------------------------------------
    // StoreController
    // ----------------------------------------------------------------

    public function test_正常な入力で予約が確定しリダイレクトされる(): void
    {
        [$slot, $plan] = $this->makeAvailableSlotAndPlan();

        $this->post(route('user.reservations.store'), $this->postData($slot->id, $plan->id))
            ->assertRedirect(route('user.reservations.complete'));

        $this->assertDatabaseHas('reservations', [
            'reservation_slot_id'   => $slot->id,
            'accommodation_plan_id' => $plan->id,
        ]);
    }

    public function test_予約済みの枠には予約できずエラーが返る(): void
    {
        $roomType = RoomType::factory()->create(['count' => 1]);
        $plan     = AccommodationPlan::factory()->create();
        $slot     = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Booked,
        ]);
        PlanRoomPrice::factory()->create([
            'room_type_id'          => $roomType->id,
            'accommodation_plan_id' => $plan->id,
        ]);

        $this->post(route('user.reservations.store'), $this->postData($slot->id, $plan->id))
            ->assertSessionHasErrors('slot_id');

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_バリデーションエラー時は予約が作成されない(): void
    {
        $this->post(route('user.reservations.store'), [])
            ->assertSessionHasErrors(['slot_id', 'plan_id', 'last_name', 'first_name', 'email', 'address', 'phone']);

        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_予約完了後に宿泊者へ予約完了メールが送信される(): void
    {
        Mail::fake();
        [$slot, $plan] = $this->makeAvailableSlotAndPlan();

        $this->post(route('user.reservations.store'), $this->postData($slot->id, $plan->id));

        Mail::assertSent(ReservationConfirmedMail::class, fn ($mail) => $mail->hasTo('hanako@example.com'));
    }

    public function test_予約完了後に管理者へ予約受付メールが送信される(): void
    {
        Mail::fake();
        Admin::create([
            'last_name'  => '管理',
            'first_name' => '太郎',
            'email'      => 'admin@example.com',
            'password'   => Hash::make('password'),
        ]);
        [$slot, $plan] = $this->makeAvailableSlotAndPlan();

        $this->post(route('user.reservations.store'), $this->postData($slot->id, $plan->id));

        Mail::assertSent(ReservationReceivedMail::class, fn ($mail) => $mail->hasTo('admin@example.com'));
    }

    // ----------------------------------------------------------------
    // CompleteController
    // ----------------------------------------------------------------

    public function test_予約完了ページが表示される(): void
    {
        $this->get(route('user.reservations.complete'))
            ->assertOk();
    }

    public function test_予約完了ページに他プラン誘導リンクがある(): void
    {
        $this->get(route('user.reservations.complete'))
            ->assertOk()
            ->assertSee('他のプランを見る');
    }

    // ----------------------------------------------------------------
    // ヘルパー
    // ----------------------------------------------------------------

    /** @return array{0: ReservationSlot, 1: AccommodationPlan} */
    private function makeAvailableSlotAndPlan(): array
    {
        $roomType = RoomType::factory()->create(['count' => 3]);
        $plan     = AccommodationPlan::factory()->create();
        $slot     = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Available,
        ]);
        PlanRoomPrice::factory()->create([
            'room_type_id'          => $roomType->id,
            'accommodation_plan_id' => $plan->id,
        ]);

        return [$slot, $plan];
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
