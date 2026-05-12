<?php

namespace App\Http\Requests\Admin\Plan;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'images'             => ['nullable', 'array'],
            'images.*'           => ['image', 'max:15360', 'mimes:jpg,jpeg,png,webp'],
            'prices'             => ['nullable', 'array'],
            'prices.*'           => ['nullable', 'integer', 'min:0'],
            'delete_image_ids'   => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:plan_images,id'],
        ];
    }
}
