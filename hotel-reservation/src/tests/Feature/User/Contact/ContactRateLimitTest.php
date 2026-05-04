<?php

namespace Tests\Feature\User\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactRateLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    public function test_1分間に10回を超えてお問い合わせを送信するとレート制限される(): void
    {
        Mail::fake();

        for ($i = 0; $i < 10; $i++) {
            $this->post(route('user.contact.store'), $this->postData())
                ->assertRedirect();
        }

        $this->post(route('user.contact.store'), $this->postData())
            ->assertStatus(429);
    }

    /** @return array<string, mixed> */
    private function postData(): array
    {
        return [
            'last_name'  => '田中',
            'first_name' => '花子',
            'email'      => 'hanako@example.com',
            'address'    => '大阪府大阪市1-1-1',
            'phone'      => '0612345678',
            'message'    => 'テストです。',
        ];
    }
}
