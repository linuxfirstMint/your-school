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

        Mail::send(new InquiryCompletedMail($inquiry));

        foreach (Admin::all() as $admin) {
            Mail::send(new InquiryReceivedMail($inquiry, $admin->email));
        }

        return $inquiry;
    }
}
