<?php

namespace Database\Factories;

use App\Models\AccommodationPlan;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_slot_id' => ReservationSlot::factory(),
            'accommodation_plan_id' => AccommodationPlan::factory(),
            'plan_name' => $this->faker->sentence(3),
            'price' => $this->faker->numberBetween(5000, 50000),
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'email' => $this->faker->safeEmail(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'message' => $this->faker->optional()->sentence(),
            'status' => 1,
            'memo' => null,
        ];
    }
}
