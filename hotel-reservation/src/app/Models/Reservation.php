<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'reservation_slot_id',
        'accommodation_plan_id',
        'plan_name',
        'price',
        'last_name',
        'first_name',
        'email',
        'address',
        'phone',
        'message',
        'status',
        'memo',
    ];

    protected $casts = [
        'status' => ReservationStatus::class,
    ];

    /** @return BelongsTo<ReservationSlot, $this> */
    public function reservationSlot(): BelongsTo
    {
        return $this->belongsTo(ReservationSlot::class);
    }

    /** @return BelongsTo<AccommodationPlan, $this> */
    public function accommodationPlan(): BelongsTo
    {
        return $this->belongsTo(AccommodationPlan::class);
    }
}
