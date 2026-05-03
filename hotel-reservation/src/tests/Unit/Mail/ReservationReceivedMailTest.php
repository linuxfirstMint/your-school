<?php

namespace Tests\Unit\Mail;

use App\Mail\ReservationReceivedMail;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationReceivedMailTest extends TestCase
{
    use RefreshDatabase;

    private Reservation $reservation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservation = Reservation::factory()->create([
            'last_name'  => '田中',
            'first_name' => '花子',
            'email'      => 'guest@example.com',
            'plan_name'  => 'スタンダードプラン',
            'price'      => 12000,
        ]);
    }

    public function test_件名が予約受付のお知らせである(): void
    {
        $mail = new ReservationReceivedMail($this->reservation, 'admin@example.com');
        $mail->assertHasSubject('予約受付のお知らせ');
    }

    public function test_宛先が指定した管理者メールアドレスである(): void
    {
        $mail = new ReservationReceivedMail($this->reservation, 'admin@example.com');
        $mail->assertHasTo('admin@example.com');
    }

    public function test_本文に宿泊者の氏名が含まれる(): void
    {
        $mail = new ReservationReceivedMail($this->reservation, 'admin@example.com');
        $mail->assertSeeInHtml('田中');
        $mail->assertSeeInHtml('花子');
    }

    public function test_本文にプラン名が含まれる(): void
    {
        $mail = new ReservationReceivedMail($this->reservation, 'admin@example.com');
        $mail->assertSeeInHtml('スタンダードプラン');
    }
}
