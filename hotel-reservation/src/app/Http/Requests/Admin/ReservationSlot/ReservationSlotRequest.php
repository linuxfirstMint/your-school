<?php

namespace App\Http\Requests\Admin\ReservationSlot;

use Illuminate\Foundation\Http\FormRequest;

class ReservationSlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'start'        => ['required', 'date', 'before:end'],
            'end'          => ['required', 'date', 'after:start'],
        ];
    }
}
