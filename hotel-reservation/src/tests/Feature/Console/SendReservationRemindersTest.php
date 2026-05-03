<?php

namespace Tests\Feature\Console;

use App\Enums\ReservationStatus;
use App\Mail\ReservationReminderMail;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendReservationRemindersTest extends TestCase
{
    use RefreshDatabase;

    public function test_翌日チェックインの確定予約にリマインドメールが送信される(): void
    {
        Mail::fake();

        $tomorrow = now()->addDay()->toDateString();
        $slot = ReservationSlot::factory()->create(['start' => $tomorrow]);
        Reservation::factory()->create([
            'reservation_slot_id' => $slot->id,
            'status'              => ReservationStatus::Confirmed,
            'email'               => 'guest@example.com',
        ]);

        $this->artisan('reservations:send-reminders')->assertSuccessful();

        Mail::assertSent(ReservationReminderMail::class, 1);
        Mail::assertSent(ReservationReminderMail::class, fn ($mail) => $mail->hasTo('guest@example.com'));
    }

    public function test_翌日以外のチェックインにはメールを送らない(): void
    {
        Mail::fake();

        $dayAfterTomorrow = now()->addDays(2)->toDateString();
        $slot = ReservationSlot::factory()->create(['start' => $dayAfterTomorrow]);
        Reservation::factory()->create([
            'reservation_slot_id' => $slot->id,
            'status'              => ReservationStatus::Confirmed,
        ]);

        $this->artisan('reservations:send-reminders')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_キャンセル済み予約にはメールを送らない(): void
    {
        Mail::fake();

        $tomorrow = now()->addDay()->toDateString();
        $slot = ReservationSlot::factory()->create(['start' => $tomorrow]);
        Reservation::factory()->create([
            'reservation_slot_id' => $slot->id,
            'status'              => ReservationStatus::Cancelled,
        ]);

        $this->artisan('reservations:send-reminders')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_複数の翌日予約に一括送信される(): void
    {
        Mail::fake();

        $tomorrow = now()->addDay()->toDateString();
        $slots = ReservationSlot::factory()->count(3)->create(['start' => $tomorrow]);
        foreach ($slots as $slot) {
            Reservation::factory()->create([
                'reservation_slot_id' => $slot->id,
                'status'              => ReservationStatus::Confirmed,
            ]);
        }

        $this->artisan('reservations:send-reminders')->assertSuccessful();

        Mail::assertSent(ReservationReminderMail::class, 3);
    }

    public function test_翌日チェックインの対象予約が0件の場合は正常終了する(): void
    {
        Mail::fake();

        $this->artisan('reservations:send-reminders')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_送信件数がコンソールに出力される(): void
    {
        Mail::fake();

        $tomorrow = now()->addDay()->toDateString();
        $slots = ReservationSlot::factory()->count(3)->create(['start' => $tomorrow]);
        foreach ($slots as $slot) {
            Reservation::factory()->create([
                'reservation_slot_id' => $slot->id,
                'status'              => ReservationStatus::Confirmed,
            ]);
        }

        $this->artisan('reservations:send-reminders')
            ->assertSuccessful()
            ->expectsOutput('リマインドメールを 3 件送信しました。');
    }

    public function test_対象が0件の場合も件数がコンソールに出力される(): void
    {
        Mail::fake();

        $this->artisan('reservations:send-reminders')
            ->assertSuccessful()
            ->expectsOutput('リマインドメールを 0 件送信しました。');
    }

    public function test_送信されるメールに正しい予約者情報が含まれる(): void
    {
        Mail::fake();

        $tomorrow = now()->addDay()->toDateString();
        $slot = ReservationSlot::factory()->create(['start' => $tomorrow]);
        Reservation::factory()->create([
            'reservation_slot_id' => $slot->id,
            'status'              => ReservationStatus::Confirmed,
            'email'               => 'taro@example.com',
            'last_name'           => '山田',
            'first_name'          => '太郎',
            'plan_name'           => '特別プラン',
        ]);

        $this->artisan('reservations:send-reminders')->assertSuccessful();

        Mail::assertSent(ReservationReminderMail::class, function ($mail) {
            return $mail->hasTo('taro@example.com')
                && $mail->reservation->last_name === '山田'
                && $mail->reservation->plan_name === '特別プラン';
        });
    }
}
