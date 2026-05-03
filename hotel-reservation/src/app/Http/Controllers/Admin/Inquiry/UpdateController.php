<?php

namespace App\Http\Controllers\Admin\Inquiry;

use App\Enums\InquiryStatus;
use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:' . implode(',', array_column(InquiryStatus::cases(), 'value'))],
        ]);

        $inquiry->update(['status' => $validated['status']]);

        return redirect()->route('admin.inquiries.show', $inquiry);
    }
}
