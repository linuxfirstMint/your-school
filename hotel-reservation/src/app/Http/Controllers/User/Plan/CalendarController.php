<?php

namespace App\Http\Controllers\User\Plan;

use App\Http\Controllers\Controller;
use App\Models\AccommodationPlan;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __invoke(Request $request, AccommodationPlan $plan, CalendarService $service): View
    {
        $year       = $request->integer('year', (int) now()->format('Y'));
        $month      = $request->integer('month', (int) now()->format('n'));
        $roomTypeId = $request->integer('room_type_id') ?: null;

        $roomTypes = $plan->planRoomPrices()->with('roomType')->get()->pluck('roomType');
        $calendar  = $service->getMonthlyAvailability($plan, $year, $month, $roomTypeId);
        $firstDay  = Carbon::create($year, $month, 1);

        return view('user.plan.calendar', compact('plan', 'calendar', 'firstDay', 'roomTypes', 'roomTypeId'));
    }
}
