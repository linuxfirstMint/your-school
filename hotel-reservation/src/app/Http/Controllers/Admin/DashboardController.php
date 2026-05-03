<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InquiryStatus;
use App\Enums\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Reservation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today    = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $todayCount = Reservation::query()
            ->where('status', ReservationStatus::Confirmed)
            ->whereHas('reservationSlot', fn ($q) => $q->whereDate('start', $today))
            ->count();

        $tomorrowCount = Reservation::query()
            ->where('status', ReservationStatus::Confirmed)
            ->whereHas('reservationSlot', fn ($q) => $q->whereDate('start', $tomorrow))
            ->count();

        $pendingInquiryCount = Inquiry::where('status', InquiryStatus::Pending)->count();

        $monthlyCount = Reservation::query()
            ->where('status', ReservationStatus::Confirmed)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $recentReservations = Reservation::query()
            ->with('reservationSlot')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayCount',
            'tomorrowCount',
            'pendingInquiryCount',
            'monthlyCount',
            'recentReservations',
        ));
    }
}
