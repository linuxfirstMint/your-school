<?php

namespace App\Http\Requests\User\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'slot_id'    => ['required', 'exists:reservation_slots,id'],
            'plan_id'    => ['required', 'exists:accommodation_plans,id'],
            'last_name'  => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'address'    => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'max:20'],
            'message'    => ['nullable', 'string'],
        ];
    }
}
