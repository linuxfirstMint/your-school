<?php

namespace Tests\Feature\Admin\Reservation;

use App\Enums\ReservationSlotStatus;
use App\Enums\ReservationStatus;
use App\Mail\ReservationCancelledMail;
use App\Models\AccommodationPlan;
use App\Models\Admin;
use App\Models\PlanRoomPrice;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
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
        $this->get(route('admin.reservations.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーはキャンセルできない(): void
    {
        $reservation = $this->makeConfirmedReservation();

        $this->delete(route('admin.reservations.cancel', $reservation))
            ->assertRedirect(route('admin.login'));
    }

    // ----------------------------------------------------------------
    // IndexController
    // ----------------------------------------------------------------

    public function test_一覧ページが表示される(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reservations.index'))
            ->assertOk();
    }

    public function test_予約データが一覧に表示される(): void
    {
        $reservation = $this->makeConfirmedReservation();

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reservations.index'))
            ->assertOk()
            ->assertSee($reservation->last_name);
    }

    public function test_予約一覧に確定ステータスが日本語で表示される(): void
    {
        $this->makeConfirmedReservation();

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reservations.index'))
            ->assertOk()
            ->assertSee('確定');
    }

    public function test_予約一覧にキャンセル済みステータスが日本語で表示される(): void
    {
        $reservation = $this->makeConfirmedReservation();
        $reservation->update(['status' => ReservationStatus::Cancelled]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reservations.index'))
            ->assertOk()
            ->assertSee('キャンセル済み');
    }

    // ----------------------------------------------------------------
    // CancelController
    // ----------------------------------------------------------------

    public function test_予約をキャンセルできる(): void
    {
        $reservation = $this->makeConfirmedReservation();

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.reservations.cancel', $reservation))
            ->assertRedirect(route('admin.reservations.index'));

        $this->assertSame(ReservationStatus::Cancelled, $reservation->fresh()->status);
        $this->assertSame(ReservationSlotStatus::Available, $reservation->reservationSlot->fresh()->status);
    }

    public function test_予約キャンセル後に宿泊者へキャンセル完了メールが送信される(): void
    {
        Mail::fake();
        $reservation = $this->makeConfirmedReservation();

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.reservations.cancel', $reservation));

        Mail::assertQueued(ReservationCancelledMail::class, fn ($mail) => $mail->hasTo($reservation->email));
    }

    public function test_キャンセル済みの予約を再キャンセルしても冪等に動作する(): void
    {
        $reservation = $this->makeConfirmedReservation();
        $reservation->update(['status' => ReservationStatus::Cancelled]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.reservations.cancel', $reservation))
            ->assertRedirect(route('admin.reservations.index'));

        $this->assertSame(ReservationStatus::Cancelled, $reservation->fresh()->status);
    }

    // ----------------------------------------------------------------
    // ヘルパー
    // ----------------------------------------------------------------

    private function makeConfirmedReservation(): Reservation
    {
        $roomType = RoomType::factory()->create();
        $plan     = AccommodationPlan::factory()->create();
        $slot     = ReservationSlot::factory()->create([
            'room_type_id' => $roomType->id,
            'status'       => ReservationSlotStatus::Booked,
        ]);
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType->id,
        ]);

        return Reservation::factory()->create([
            'reservation_slot_id'   => $slot->id,
            'accommodation_plan_id' => $plan->id,
            'last_name'             => '田中',
            'status'                => ReservationStatus::Confirmed,
        ]);
    }
}
