<?php

namespace App\Http\Controllers\User\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Contact\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function __invoke(ContactRequest $request, ContactService $service): RedirectResponse
    {
        $validated = $request->validated();

        $service->store($validated);

        return redirect()->route('user.contact.complete');
    }
}
