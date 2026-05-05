<?php

namespace App\Http\Requests\User\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'last_name'  => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255'],
            'address'    => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'max:20'],
            'message'    => ['nullable', 'string'],
        ];
    }
}
