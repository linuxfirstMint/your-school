<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryCompletedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Inquiry $inquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to:      [$this->inquiry->email],
            subject: 'お問い合わせありがとうございます',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.inquiry.completed',
        );
    }
}
