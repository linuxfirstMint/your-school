<?php

namespace Database\Seeders;

use App\Enums\ReservationSlotStatus;
use App\Enums\ReservationStatus;
use App\Models\AccommodationPlan;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $bookedSlots = ReservationSlot::where('status', ReservationSlotStatus::Booked)->get();

        foreach ($bookedSlots as $slot) {
            $plan = AccommodationPlan::whereHas('planRoomPrices', function ($q) use ($slot) {
                $q->where('room_type_id', $slot->room_type_id);
            })->inRandomOrder()->first();

            if (! $plan) {
                continue;
            }

            $price = $plan->planRoomPrices
                ->firstWhere('room_type_id', $slot->room_type_id)
                ?->price ?? 0;

            Reservation::create([
                'reservation_slot_id'   => $slot->id,
                'accommodation_plan_id' => $plan->id,
                'plan_name'             => $plan->name,
                'price'                 => $price,
                'last_name'             => fake('ja_JP')->lastName(),
                'first_name'            => fake('ja_JP')->firstName(),
                'email'                 => fake()->safeEmail(),
                'address'               => fake('ja_JP')->address(),
                'phone'                 => fake('ja_JP')->phoneNumber(),
                'message'               => fake()->optional(0.4)->sentence(),
                'status'                => fake()->randomElement([
                    ReservationStatus::Confirmed,
                    ReservationStatus::Confirmed,
                    ReservationStatus::Cancelled,
                ]),
            ]);
        }
    }
}
