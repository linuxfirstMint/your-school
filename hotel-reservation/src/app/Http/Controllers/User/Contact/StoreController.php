<?php

namespace App\Http\Controllers\User\Contact;

use App\Http\Controllers\Controller;
use App\Mail\InquiryCompletedMail;
use App\Mail\InquiryReceivedMail;
use App\Models\Admin;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class StoreController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'last_name'  => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'address'    => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
            'message'    => 'nullable|string',
        ]);

        $inquiry = Inquiry::create($validated);

        Mail::send(new InquiryCompletedMail($inquiry));

        foreach (Admin::all() as $admin) {
            Mail::send(new InquiryReceivedMail($inquiry, $admin->email));
        }

        return redirect()->route('user.contact.complete');
    }
}
