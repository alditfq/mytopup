<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'game_id' => 'required|exists:games,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rank' => 'required|string',
            'level' => 'required|integer|min:1',
            'skin_count' => 'required|integer|min:0',
            'login_method' => 'required|string',
            'bind_status' => 'required|string',
            'price' => 'required|integer|min:0',
            'account_data' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }
}
