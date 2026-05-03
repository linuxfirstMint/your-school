<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationCancelledMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Reservation $reservation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to:      [$this->reservation->email],
            subject: '予約キャンセルのお知らせ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.reservation.cancelled',
        );
    }
}
