<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReceivedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Inquiry $inquiry,
        public readonly string $adminEmail,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to:      [$this->adminEmail],
            subject: 'お問い合わせを受け付けました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.inquiry.received',
        );
    }
}
