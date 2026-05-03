<?php

namespace Tests\Unit\Mail;

use App\Mail\InquiryCompletedMail;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryCompletedMailTest extends TestCase
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

    public function test_件名がお問い合わせありがとうございますである(): void
    {
        $mail = new InquiryCompletedMail($this->inquiry);
        $mail->assertHasSubject('お問い合わせありがとうございます');
    }

    public function test_宛先がお問い合わせ者のメールアドレスである(): void
    {
        $mail = new InquiryCompletedMail($this->inquiry);
        $mail->assertHasTo('guest@example.com');
    }

    public function test_本文にお問い合わせ者の氏名が含まれる(): void
    {
        $mail = new InquiryCompletedMail($this->inquiry);
        $mail->assertSeeInHtml('田中');
        $mail->assertSeeInHtml('花子');
    }
}
