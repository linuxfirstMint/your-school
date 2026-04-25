<?php

namespace Database\Factories;

use App\Enums\ReservationSlotStatus;
use App\Models\ReservationSlot;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationSlot>
 */
class ReservationSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+6 months');
        $end = (clone $start)->modify('+1 day');

        return [
            'room_type_id' => RoomType::factory(),
            'status' => ReservationSlotStatus::Available,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
        ];
    }
}
