<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationReceivedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Reservation $reservation,
        public readonly string $adminEmail,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to:      [$this->adminEmail],
            subject: '予約受付のお知らせ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.reservation.received',
        );
    }
}
