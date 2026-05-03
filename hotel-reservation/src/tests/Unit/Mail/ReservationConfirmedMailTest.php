<?php

namespace Tests\Unit\Mail;

use App\Mail\ReservationConfirmedMail;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationConfirmedMailTest extends TestCase
{
    use RefreshDatabase;

    private Reservation $reservation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservation = Reservation::factory()->create([
            'last_name'  => '山田',
            'first_name' => '太郎',
            'email'      => 'guest@example.com',
            'plan_name'  => 'スタンダードプラン',
            'price'      => 12000,
        ]);
    }

    public function test_件名が予約完了のお知らせである(): void
    {
        $mail = new ReservationConfirmedMail($this->reservation);
        $mail->assertHasSubject('予約完了のお知らせ');
    }

    public function test_宛先が予約者のメールアドレスである(): void
    {
        $mail = new ReservationConfirmedMail($this->reservation);
        $mail->assertHasTo('guest@example.com');
    }

    public function test_本文に宿泊者の氏名が含まれる(): void
    {
        $mail = new ReservationConfirmedMail($this->reservation);
        $mail->assertSeeInHtml('山田');
        $mail->assertSeeInHtml('太郎');
    }

    public function test_本文にプラン名が含まれる(): void
    {
        $mail = new ReservationConfirmedMail($this->reservation);
        $mail->assertSeeInHtml('スタンダードプラン');
    }
}
