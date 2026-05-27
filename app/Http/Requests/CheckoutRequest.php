<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'game_id' => 'required|exists:games,id',
            'target_id' => $this->has('game_account_id') ? 'required|email' : 'required|string',
            'zone_id' => 'nullable|string',
            'nominal_id' => $this->has('game_account_id') ? 'nullable|exists:nominals,id' : 'required|exists:nominals,id',
            'game_account_id' => 'nullable|exists:game_accounts,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'promo_code' => 'nullable|string'
        ];
    }
}
