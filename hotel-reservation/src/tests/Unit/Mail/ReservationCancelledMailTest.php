<?php

namespace Tests\Unit\Mail;

use App\Mail\ReservationCancelledMail;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCancelledMailTest extends TestCase
{
    use RefreshDatabase;

    private Reservation $reservation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservation = Reservation::factory()->create([
            'last_name'  => '田中',
            'first_name' => '花子',
            'email'      => 'guest2@example.com',
            'plan_name'  => 'スタンダードプラン',
            'price'      => 12000,
        ]);
    }

    public function test_件名が予約キャンセルのお知らせである(): void
    {
        $mail = new ReservationCancelledMail($this->reservation);
        $mail->assertHasSubject('予約キャンセルのお知らせ');
    }

    public function test_宛先が予約者のメールアドレスである(): void
    {
        $mail = new ReservationCancelledMail($this->reservation);
        $mail->assertHasTo('guest2@example.com');
    }

    public function test_本文に宿泊者の氏名が含まれる(): void
    {
        $mail = new ReservationCancelledMail($this->reservation);
        $mail->assertSeeInHtml('田中');
        $mail->assertSeeInHtml('花子');
    }

    public function test_本文にプラン名が含まれる(): void
    {
        $mail = new ReservationCancelledMail($this->reservation);
        $mail->assertSeeInHtml('スタンダードプラン');
    }
}
