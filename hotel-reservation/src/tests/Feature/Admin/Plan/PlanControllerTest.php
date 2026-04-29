<?php

namespace Tests\Feature\Admin\Plan;

use App\Models\AccommodationPlan;
use App\Models\Admin;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlanControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
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
        $this->get(route('admin.plans.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは作成できない(): void
    {
        $this->post(route('admin.plans.store'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは編集フォームにアクセスできない(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->get(route('admin.plans.edit', $plan))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは更新できない(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->put(route('admin.plans.update', $plan))
            ->assertRedirect(route('admin.login'));
    }

    public function test_未認証ユーザーは削除できない(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->delete(route('admin.plans.destroy', $plan))
            ->assertRedirect(route('admin.login'));
    }

    // ----------------------------------------------------------------
    // IndexController
    // ----------------------------------------------------------------

    public function test_一覧ページが表示される(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.plans.index'))
            ->assertOk();
    }

    public function test_プランの一覧が表示される(): void
    {
        $plan = AccommodationPlan::factory()->create(['name' => '絶景プラン']);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.plans.index'))
            ->assertOk()
            ->assertSee('絶景プラン');
    }

    // ----------------------------------------------------------------
    // StoreController
    // ----------------------------------------------------------------

    public function test_正常な入力でプランを作成できる(): void
    {
        $roomType = RoomType::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.plans.store'), [
                'name'        => '新プラン',
                'description' => '説明文',
                'prices'      => [$roomType->id => 12000],
            ])
            ->assertRedirect(route('admin.plans.index'));

        $this->assertDatabaseHas('accommodation_plans', ['name' => '新プラン']);
        $this->assertDatabaseHas('plan_room_prices', [
            'room_type_id' => $roomType->id,
            'price'        => 12000,
        ]);
    }

    public function test_画像付きでプランを作成できる(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.plans.store'), [
                'name'   => '画像付きプラン',
                'images' => [UploadedFile::fake()->image('room.jpg')],
            ])
            ->assertRedirect(route('admin.plans.index'));

        $plan = AccommodationPlan::first();
        $this->assertCount(1, $plan->planImages);
    }

    public function test_バリデーションエラー時はプランが作成されない(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.plans.store'), [])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('accommodation_plans', 0);
    }

    // ----------------------------------------------------------------
    // EditController
    // ----------------------------------------------------------------

    public function test_編集フォームが表示される(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.plans.edit', $plan))
            ->assertOk();
    }

    // ----------------------------------------------------------------
    // UpdateController
    // ----------------------------------------------------------------

    public function test_正常な入力でプランを更新できる(): void
    {
        $plan = AccommodationPlan::factory()->create(['name' => '旧プラン']);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.plans.update', $plan), [
                'name' => '新プラン',
            ])
            ->assertRedirect(route('admin.plans.index'));

        $this->assertDatabaseHas('accommodation_plans', [
            'id'   => $plan->id,
            'name' => '新プラン',
        ]);
    }

    public function test_バリデーションエラー時はプランが更新されない(): void
    {
        $plan = AccommodationPlan::factory()->create(['name' => '元のプラン']);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.plans.update', $plan), ['name' => ''])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseHas('accommodation_plans', [
            'id'   => $plan->id,
            'name' => '元のプラン',
        ]);
    }

    // ----------------------------------------------------------------
    // DestroyController
    // ----------------------------------------------------------------

    public function test_プランを削除できる(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.plans.destroy', $plan))
            ->assertRedirect(route('admin.plans.index'));

        $this->assertSoftDeleted('accommodation_plans', ['id' => $plan->id]);
    }
}
