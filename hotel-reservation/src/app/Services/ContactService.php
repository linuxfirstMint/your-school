<?php

namespace App\Services;

use App\Mail\InquiryCompletedMail;
use App\Mail\InquiryReceivedMail;
use App\Models\Admin;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    /** @param array<string, mixed> $data */
    public function store(array $data): Inquiry
    {
        $inquiry = Inquiry::create($data);

        Mail::queue(new InquiryCompletedMail($inquiry));

        $adminEmails = Admin::pluck('email')->all();
        if ($adminEmails !== []) {
            Mail::queue(new InquiryReceivedMail($inquiry, $adminEmails));
        }

        return $inquiry;
    }
}
