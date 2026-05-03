<?php

namespace Tests\Feature\Admin\Inquiry;

use App\Enums\InquiryStatus;
use App\Models\Admin;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InquiryControllerTest extends TestCase
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

    // ----------------------------------------------------------------
    // 未認証チェック
    // ----------------------------------------------------------------

    public function test_未認証ユーザーは一覧にアクセスできない(): void
    {
        $this->get(route('admin.inquiries.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは詳細にアクセスできない(): void
    {
        $inquiry = Inquiry::factory()->create();

        $this->get(route('admin.inquiries.show', $inquiry))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーはステータス更新できない(): void
    {
        $inquiry = Inquiry::factory()->create();

        $this->put(route('admin.inquiries.update', $inquiry), ['status' => InquiryStatus::InProgress->value])
            ->assertRedirect(route('admin.login'));
    }

    // ----------------------------------------------------------------
    // IndexController
    // ----------------------------------------------------------------

    public function test_一覧ページが表示される(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.inquiries.index'))
            ->assertOk();
    }

    public function test_お問い合わせ一覧が表示される(): void
    {
        $inquiry = Inquiry::factory()->create([
            'last_name'  => '鈴木',
            'first_name' => '花子',
            'email'      => 'hanako@example.com',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.inquiries.index'))
            ->assertSee('鈴木')
            ->assertSee('花子')
            ->assertSee('hanako@example.com');
    }

    public function test_一覧にステータスが日本語で表示される(): void
    {
        Inquiry::factory()->create(['status' => InquiryStatus::Pending]);
        Inquiry::factory()->create(['status' => InquiryStatus::InProgress]);
        Inquiry::factory()->create(['status' => InquiryStatus::Resolved]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.inquiries.index'))
            ->assertSee('未対応')
            ->assertSee('対応中')
            ->assertSee('対応完了');
    }

    // ----------------------------------------------------------------
    // ShowController
    // ----------------------------------------------------------------

    public function test_詳細ページが表示される(): void
    {
        $inquiry = Inquiry::factory()->create([
            'last_name'  => '田中',
            'first_name' => '一郎',
            'message'    => 'テストメッセージ',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.inquiries.show', $inquiry))
            ->assertOk()
            ->assertSee('田中')
            ->assertSee('一郎')
            ->assertSee('テストメッセージ');
    }

    // ----------------------------------------------------------------
    // UpdateController (ステータス変更)
    // ----------------------------------------------------------------

    public function test_ステータスを対応中に更新できる(): void
    {
        $inquiry = Inquiry::factory()->create(['status' => InquiryStatus::Pending]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.inquiries.update', $inquiry), ['status' => InquiryStatus::InProgress->value])
            ->assertRedirect(route('admin.inquiries.show', $inquiry));

        $this->assertDatabaseHas('inquiries', [
            'id'     => $inquiry->id,
            'status' => InquiryStatus::InProgress->value,
        ]);
    }

    public function test_ステータスを対応完了に更新できる(): void
    {
        $inquiry = Inquiry::factory()->create(['status' => InquiryStatus::InProgress]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.inquiries.update', $inquiry), ['status' => InquiryStatus::Resolved->value])
            ->assertRedirect(route('admin.inquiries.show', $inquiry));

        $this->assertDatabaseHas('inquiries', [
            'id'     => $inquiry->id,
            'status' => InquiryStatus::Resolved->value,
        ]);
    }

    public function test_ステータスを未対応に戻せる(): void
    {
        $inquiry = Inquiry::factory()->create(['status' => InquiryStatus::Resolved]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.inquiries.update', $inquiry), ['status' => InquiryStatus::Pending->value])
            ->assertRedirect(route('admin.inquiries.show', $inquiry));

        $this->assertDatabaseHas('inquiries', [
            'id'     => $inquiry->id,
            'status' => InquiryStatus::Pending->value,
        ]);
    }
}
