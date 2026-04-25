<?php

namespace Database\Factories;

use App\Models\AccommodationPlan;
use App\Models\PlanRoomPrice;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlanRoomPrice>
 */
class PlanRoomPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_type_id' => RoomType::factory(),
            'accommodation_plan_id' => AccommodationPlan::factory(),
            'price' => $this->faker->numberBetween(5000, 50000),
        ];
    }
}
