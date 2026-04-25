<?php

namespace App\Models;

use App\Enums\ReservationSlotStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReservationSlot extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationSlotFactory> */
    use HasFactory;

    protected $fillable = ['room_type_id', 'status', 'start', 'end'];

    protected $casts = [
        'status' => ReservationSlotStatus::class,
        'start'  => 'date',
        'end'    => 'date',
    ];

    /** @return BelongsTo<RoomType, $this> */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /** @return HasOne<Reservation, $this> */
    public function reservation(): HasOne
    {
        return $this->hasOne(Reservation::class);
    }
}
