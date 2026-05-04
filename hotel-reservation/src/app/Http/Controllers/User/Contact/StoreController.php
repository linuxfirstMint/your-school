<?php

namespace App\Http\Controllers\User\Contact;

use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request, ContactService $service): RedirectResponse
    {
        $validated = $request->validate([
            'last_name'  => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'address'    => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
            'message'    => 'nullable|string',
        ]);

        $service->store($validated);

        return redirect()->route('user.contact.complete');
    }
}
