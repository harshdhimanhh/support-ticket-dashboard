<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_type' => [
                'required',
                Rule::in(['agent', 'customer']),
            ],
            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ];
    }
}
