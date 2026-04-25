<?php

namespace Database\Factories;

use App\Models\AccommodationPlan;
use App\Models\PlanImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlanImage>
 */
class PlanImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'accommodation_plan_id' => AccommodationPlan::factory(),
            'name' => $this->faker->word(),
            'image_path' => 'images/' . $this->faker->uuid() . '.jpg',
        ];
    }
}
