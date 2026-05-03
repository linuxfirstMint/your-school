<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLayoutTest extends TestCase
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

    public function test_ヘッダーに表サイトへのリンクがある(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.plans.index'))
            ->assertOk()
            ->assertSee('表サイトへ');
    }

    public function test_サイドバーにダッシュボードリンクがある(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.plans.index'))
            ->assertOk()
            ->assertSee('ダッシュボード');
    }

    public function test_サイドバーにお問い合わせ一覧メニューがある(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.plans.index'))
            ->assertOk()
            ->assertSee('お問い合わせ');
    }

    public function test_スマホ向けOffcanvasメニューにナビゲーションがある(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.plans.index'))
            ->assertOk()
            ->assertSee('offcanvas');
    }
}
