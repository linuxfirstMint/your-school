<?php

namespace Tests\Feature\User\Plan;

use App\Models\AccommodationPlan;
use App\Models\PlanImage;
use App\Models\PlanRoomPrice;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanControllerTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // IndexController
    // ----------------------------------------------------------------

    public function test_プラン一覧ページが表示される(): void
    {
        $this->get(route('user.plans.index'))
            ->assertOk();
    }

    public function test_プランの一覧が表示される(): void
    {
        AccommodationPlan::factory()->create(['name' => '絶景プラン']);

        $this->get(route('user.plans.index'))
            ->assertOk()
            ->assertSee('絶景プラン');
    }

    public function test_プラン一覧にプランの画像が表示される(): void
    {
        $plan = AccommodationPlan::factory()->create();
        PlanImage::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'image_path'            => 'plans/test-image.jpg',
        ]);

        $this->get(route('user.plans.index'))
            ->assertOk()
            ->assertSee('plans/test-image.jpg', false);
    }

    public function test_プラン一覧に最低料金が表示される(): void
    {
        $plan = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'price'                 => 12000,
        ]);

        $this->get(route('user.plans.index'))
            ->assertOk()
            ->assertSee('12,000');
    }

    public function test_1ページに12件までしか表示されない(): void
    {
        AccommodationPlan::factory()->count(13)->create();

        $this->get(route('user.plans.index'))
            ->assertOk()
            ->assertViewHas('plans', fn ($plans) => $plans->count() === 12);
    }

    public function test_2ページ目には残りのプランが表示される(): void
    {
        AccommodationPlan::factory()->count(13)->sequence(
            fn ($s) => ['name' => "プラン{$s->index}"]
        )->create();

        $last = AccommodationPlan::latest('id')->first();

        $this->get(route('user.plans.index') . '?page=2')
            ->assertOk()
            ->assertSee($last->name);
    }

    public function test_削除済みのプランは一覧に表示されない(): void
    {
        $plan = AccommodationPlan::factory()->create(['name' => '削除済みプラン']);
        $plan->delete();

        $this->get(route('user.plans.index'))
            ->assertOk()
            ->assertDontSee('削除済みプラン');
    }

    // ----------------------------------------------------------------
    // ShowController
    // ----------------------------------------------------------------

    public function test_プラン詳細ページが表示される(): void
    {
        $plan = AccommodationPlan::factory()->create(['name' => 'スタンダードプラン']);

        $this->get(route('user.plans.show', $plan))
            ->assertOk()
            ->assertSee('スタンダードプラン');
    }

    public function test_プラン詳細ページに料金が表示される(): void
    {
        $roomType = RoomType::factory()->create(['name' => 'デラックスルーム']);
        $plan     = AccommodationPlan::factory()->create();
        PlanRoomPrice::factory()->create([
            'accommodation_plan_id' => $plan->id,
            'room_type_id'          => $roomType->id,
            'price'                 => 15000,
        ]);

        $this->get(route('user.plans.show', $plan))
            ->assertOk()
            ->assertSee('デラックスルーム')
            ->assertSee('15,000');
    }

    public function test_プラン詳細ページに空室カレンダーへのリンクがある(): void
    {
        $plan = AccommodationPlan::factory()->create();

        $this->get(route('user.plans.show', $plan))
            ->assertOk()
            ->assertSee(route('user.plans.calendar', $plan));
    }

    public function test_削除済みのプランの詳細は404になる(): void
    {
        $plan = AccommodationPlan::factory()->create();
        $plan->delete();

        $this->get(route('user.plans.show', $plan))
            ->assertNotFound();
    }
}
