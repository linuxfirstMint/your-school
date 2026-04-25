<?php

namespace Tests\Feature\Admin\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログインフォームが表示される(): void
    {
        $this->get(route('admin.login'))
            ->assertStatus(200);
    }

    public function test_正しい認証情報でログインできる(): void
    {
        $admin = Admin::create([
            'last_name' => '山田',
            'first_name' => '太郎',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_間違った認証情報ではログインできない(): void
    {
        Admin::create([
            'last_name' => '山田',
            'first_name' => '太郎',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('email');

        $this->assertGuest('admin');
    }

    public function test_未認証でダッシュボードにアクセスするとログイン画面にリダイレクトされる(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_ログアウトできる(): void
    {
        $admin = Admin::create([
            'last_name' => '山田',
            'first_name' => '太郎',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest('admin');
    }
}
