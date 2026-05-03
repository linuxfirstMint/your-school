<?php

namespace App\Http\Controllers\User\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BackController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect()->route('user.contact.create')->withInput($request->all());
    }
}
