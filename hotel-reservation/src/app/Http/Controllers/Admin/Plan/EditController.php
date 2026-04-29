<?php

namespace App\Http\Controllers\Admin\Plan;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use App\Models\RoomType;
use Illuminate\Contracts\View\View;

class EditController extends Controller
{
    public function __invoke(AccommodationPlan $plan): View
    {
        $roomTypes = RoomType::orderBy('id')->get();

        return view('admin.plan.edit', compact('plan', 'roomTypes'));
    }
}
