<?php

namespace App\Http\Controllers\Admin\Inquiry;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __invoke(): View
    {
        $inquiries = Inquiry::latest()->paginate(20);

        return view('admin.inquiry.index', compact('inquiries'));
    }
}
