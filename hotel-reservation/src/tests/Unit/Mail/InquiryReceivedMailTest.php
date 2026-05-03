<?php

namespace Tests\Unit\Mail;

use App\Mail\InquiryReceivedMail;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryReceivedMailTest extends TestCase
{
    use RefreshDatabase;

    private Inquiry $inquiry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inquiry = Inquiry::factory()->create([
            'last_name'  => '田中',
            'first_name' => '花子',
            'email'      => 'guest@example.com',
            'message'    => 'テストの問い合わせです。',
        ]);
    }

    public function test_件名がお問い合わせを受け付けましたである(): void
    {
        $mail = new InquiryReceivedMail($this->inquiry, 'admin@example.com');
        $mail->assertHasSubject('お問い合わせを受け付けました');
    }

    public function test_宛先が指定した管理者メールアドレスである(): void
    {
        $mail = new InquiryReceivedMail($this->inquiry, 'admin@example.com');
        $mail->assertHasTo('admin@example.com');
    }

    public function test_本文にお問い合わせ者の氏名が含まれる(): void
    {
        $mail = new InquiryReceivedMail($this->inquiry, 'admin@example.com');
        $mail->assertSeeInHtml('田中');
        $mail->assertSeeInHtml('花子');
    }
}
