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

    /**
     * @param string[] $adminEmails
     */
    public function __construct(
        public readonly Inquiry $inquiry,
        public readonly array $adminEmails,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to:      $this->adminEmails,
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
