<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Mail\ReservationReminderMail;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReservationReminders extends Command
{
    protected $signature = 'reservations:send-reminders';

    protected $description = '翌日チェックイン予定の宿泊者にリマインドメールを送信する';

    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $reservations = Reservation::query()
            ->where('status', ReservationStatus::Confirmed)
            ->whereHas('reservationSlot', fn ($q) => $q->whereDate('start', $tomorrow))
            ->with('reservationSlot')
            ->get();

        foreach ($reservations as $reservation) {
            Mail::send(new ReservationReminderMail($reservation));
        }

        return self::SUCCESS;
    }
}
