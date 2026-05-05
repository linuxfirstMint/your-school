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

    /**
     * @param string[] $adminEmails
     */
    public function __construct(
        public readonly Reservation $reservation,
        public readonly array $adminEmails,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to:      $this->adminEmails,
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
