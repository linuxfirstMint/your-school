<?php

namespace Tests\Feature\Services;

use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\RoomType;
use App\Services\AccommodationPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccommodationPlanServiceTest extends TestCase
{
    use RefreshDatabase;

    private AccommodationPlanService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->service = new AccommodationPlanService();
    }

    public function test_プランを作成できる(): void
    {
        $plan = $this->service->store('スタンダードプラン', '快適なお部屋です。', [], []);

        $this->assertInstanceOf(AccommodationPlan::class, $plan);
        $this->assertDatabaseHas('accommodation_plans', [
            'name'        => 'スタンダードプラン',
            'description' => '快適なお部屋です。',
        ]);
    }

    public function test_プラン作成時に画像を保存できる(): void
    {
        $images = [
            UploadedFile::fake()->image('room1.jpg'),
            UploadedFile::fake()->image('room2.jpg'),
        ];

        $plan = $this->service->store('プランA', null, $images, []);

        $this->assertCount(2, $plan->planImages);
        Storage::disk('public')->assertExists($plan->planImages[0]->image_path);
        Storage::disk('public')->assertExists($plan->planImages[1]->image_path);
    }

    public function test_プラン作成時に部屋タイプ別料金を設定できる(): void
    {
        $roomType1 = RoomType::factory()->create();
        $roomType2 = RoomType::factory()->create();

        $prices = [
            $roomType1->id => 10000,
            $roomType2->id => 20000,
        ];

        $plan = $this->service->store('プランB', null, [], $prices);

        $this->assertDatabaseHas('plan_room_prices', [
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType1->id,
            'price'                 => 10000,
        ]);
        $this->assertDatabaseHas('plan_room_prices', [
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType2->id,
            'price'                 => 20000,
        ]);
    }

    public function test_プランを編集できる(): void
    {
        $plan = AccommodationPlan::factory()->create(['name' => '旧プラン名']);

        $this->service->update($plan, '新プラン名', '新しい説明', [], []);

        $this->assertDatabaseHas('accommodation_plans', [
            'id'   => $plan->id,
            'name' => '新プラン名',
        ]);
    }

    public function test_編集時に新しい画像を指定すると既存画像が置き換えられる(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $oldImage = UploadedFile::fake()->image('old.jpg');
        $this->service->store($plan->name, null, [$oldImage], []);
        // 既存画像を直接付与
        $plan->planImages()->create([
            'name'       => 'old.jpg',
            'image_path' => 'plan-images/old.jpg',
        ]);

        $newImage = UploadedFile::fake()->image('new.jpg');
        $this->service->update($plan, $plan->name, null, [$newImage], []);

        $freshImage = $plan->fresh()->planImages->first();
        $this->assertCount(1, $plan->fresh()->planImages);
        $this->assertSame(basename($freshImage->image_path), $freshImage->name);
    }

    public function test_編集時に画像を指定しない場合は既存画像が保持される(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $plan->planImages()->create([
            'name'       => 'existing.jpg',
            'image_path' => 'plan-images/existing.jpg',
        ]);

        $this->service->update($plan, $plan->name, null, [], []);

        $this->assertCount(1, $plan->fresh()->planImages);
    }

    public function test_編集時に料金を更新できる(): void
    {
        $roomType = RoomType::factory()->create();
        $plan     = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType->id,
            'price'                 => 10000,
        ]);

        $this->service->update($plan, $plan->name, null, [], [$roomType->id => 15000]);

        $this->assertDatabaseHas('plan_room_prices', [
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType->id,
            'price'                 => 15000,
        ]);
    }

    public function test_プランを削除できる(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->service->destroy($plan);

        $this->assertSoftDeleted('accommodation_plans', ['id' => $plan->id]);
    }

    public function test_削除済みプランはデフォルトクエリに含まれない(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $this->service->destroy($plan);

        $this->assertNull(AccommodationPlan::find($plan->id));
    }

    public function test_削除時に画像ファイルも削除される(): void
    {
        $plan  = AccommodationPlan::factory()->create();
        $image = UploadedFile::fake()->image('to-delete.jpg');
        $this->service->update($plan, $plan->name, null, [$image], []);
        $imagePath = $plan->fresh()->planImages->first()->image_path;
        Storage::disk('public')->assertExists($imagePath);

        $this->service->destroy($plan);

        Storage::disk('public')->assertMissing($imagePath);
    }
}
