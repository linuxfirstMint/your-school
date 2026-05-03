<?php

namespace Tests\Feature\User\Contact;

use App\Mail\InquiryCompletedMail;
use App\Mail\InquiryReceivedMail;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // CreateController
    // ----------------------------------------------------------------

    public function test_お問い合わせフォームが表示される(): void
    {
        $this->get(route('user.contact.create'))
            ->assertOk();
    }

    // ----------------------------------------------------------------
    // StoreController
    // ----------------------------------------------------------------

    public function test_正常な入力でお問い合わせが保存される(): void
    {
        $this->post(route('user.contact.store'), $this->postData())
            ->assertRedirect(route('user.contact.complete'));

        $this->assertDatabaseHas('inquiries', [
            'last_name' => '田中',
            'email'     => 'hanako@example.com',
        ]);
    }

    public function test_バリデーションエラー時はお問い合わせが保存されない(): void
    {
        $this->post(route('user.contact.store'), [])
            ->assertSessionHasErrors(['last_name', 'first_name', 'email', 'address', 'phone']);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_送信後に管理者へお問い合わせ受信メールが送信される(): void
    {
        Mail::fake();
        Admin::create([
            'last_name'  => '管理',
            'first_name' => '太郎',
            'email'      => 'admin@example.com',
            'password'   => Hash::make('password'),
        ]);

        $this->post(route('user.contact.store'), $this->postData());

        Mail::assertSent(InquiryReceivedMail::class, fn ($mail) => $mail->hasTo('admin@example.com'));
    }

    public function test_送信後に宿泊者へお問い合わせ完了メールが送信される(): void
    {
        Mail::fake();

        $this->post(route('user.contact.store'), $this->postData());

        Mail::assertSent(InquiryCompletedMail::class, fn ($mail) => $mail->hasTo('hanako@example.com'));
    }

    // ----------------------------------------------------------------
    // CompleteController
    // ----------------------------------------------------------------

    public function test_お問い合わせ完了ページが表示される(): void
    {
        $this->get(route('user.contact.complete'))
            ->assertOk();
    }

    // ----------------------------------------------------------------
    // ヘルパー
    // ----------------------------------------------------------------

    /** @return array<string, mixed> */
    private function postData(): array
    {
        return [
            'last_name'  => '田中',
            'first_name' => '花子',
            'email'      => 'hanako@example.com',
            'address'    => '大阪府大阪市1-1-1',
            'phone'      => '0612345678',
            'message'    => 'テストのお問い合わせです。',
        ];
    }
}
