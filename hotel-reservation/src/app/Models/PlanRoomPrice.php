<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanRoomPrice extends Model
{
    /** @use HasFactory<\Database\Factories\PlanRoomPriceFactory> */
    use HasFactory;

    protected $fillable = ['room_type_id', 'accommodation_plan_id', 'price'];

    /** @return BelongsTo<RoomType, $this> */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /** @return BelongsTo<AccommodationPlan, $this> */
    public function accommodationPlan(): BelongsTo
    {
        return $this->belongsTo(AccommodationPlan::class);
    }
}
