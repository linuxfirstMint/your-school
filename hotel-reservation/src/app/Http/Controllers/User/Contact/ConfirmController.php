<?php

namespace App\Http\Controllers\User\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Contact\ContactRequest;
use Illuminate\View\View;

class ConfirmController extends Controller
{
    public function __invoke(ContactRequest $request): View
    {
        $validated = $request->validated();

        return view('user.contact.confirm', compact('validated'));
    }
}
