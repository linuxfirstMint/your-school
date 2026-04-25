<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanImage extends Model
{
    /** @use HasFactory<\Database\Factories\PlanImageFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['accommodation_plan_id', 'name', 'image_path'];

    /** @return BelongsTo<AccommodationPlan, $this> */
    public function accommodationPlan(): BelongsTo
    {
        return $this->belongsTo(AccommodationPlan::class);
    }
}
