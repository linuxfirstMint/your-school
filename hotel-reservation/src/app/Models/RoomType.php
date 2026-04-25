<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    /** @use HasFactory<\Database\Factories\RoomTypeFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'count'];

    /** @return HasMany<ReservationSlot, $this> */
    public function reservationSlots(): HasMany
    {
        return $this->hasMany(ReservationSlot::class);
    }

    /** @return HasMany<PlanRoomPrice, $this> */
    public function planRoomPrices(): HasMany
    {
        return $this->hasMany(PlanRoomPrice::class);
    }
}
