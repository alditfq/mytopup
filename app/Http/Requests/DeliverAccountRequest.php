<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliverAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_email' => 'required|string',
            'account_password' => 'required|string',
            'notes' => 'nullable|string'
        ];
    }
}
