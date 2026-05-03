<?php

namespace App\Http\Controllers\User\Contact;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CompleteController extends Controller
{
    public function __invoke(): View
    {
        return view('user.contact.complete');
    }
}
