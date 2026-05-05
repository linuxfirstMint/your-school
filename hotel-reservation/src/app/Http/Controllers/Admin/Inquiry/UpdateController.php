<?php

namespace App\Http\Controllers\Admin\Inquiry;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Inquiry\UpdateInquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    public function __invoke(UpdateInquiryRequest $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validated();

        $inquiry->update(['status' => $validated['status']]);

        return redirect()->route('admin.inquiries.show', $inquiry);
    }
}
