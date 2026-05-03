<?php

namespace App\Http\Controllers\Admin\Inquiry;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\View\View;

class ShowController extends Controller
{
    public function __invoke(Inquiry $inquiry): View
    {
        return view('admin.inquiry.show', compact('inquiry'));
    }
}
