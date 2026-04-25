<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccommodationPlan extends Model
{
    /** @use HasFactory<\Database\Factories\AccommodationPlanFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    /** @return HasMany<PlanImage, $this> */
    public function planImages(): HasMany
    {
        return $this->hasMany(PlanImage::class);
    }

    /** @return HasMany<PlanRoomPrice, $this> */
    public function planRoomPrices(): HasMany
    {
        return $this->hasMany(PlanRoomPrice::class);
    }

    /** @return HasMany<Reservation, $this> */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
