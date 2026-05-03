<?php

namespace App\Http\Controllers\User\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfirmController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'last_name'  => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'address'    => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
            'message'    => 'nullable|string',
        ]);

        return view('user.contact.confirm', compact('validated'));
    }
}
