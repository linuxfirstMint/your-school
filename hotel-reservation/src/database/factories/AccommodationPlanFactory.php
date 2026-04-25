<?php

namespace Database\Factories;

use App\Models\AccommodationPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccommodationPlan>
 */
class AccommodationPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
        ];
    }
}
