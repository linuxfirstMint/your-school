<?php

namespace App\Http\Requests\Admin\Inquiry;

use App\Enums\InquiryStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', 'in:' . implode(',', array_column(InquiryStatus::cases(), 'value'))],
        ];
    }
}
