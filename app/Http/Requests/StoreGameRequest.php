<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'developer' => 'required|string',
            'id_label' => 'required|string',
            'zone_id_label' => 'nullable|string',
            'id_helper_text' => 'required|string',
            'cashback_percent' => 'required|integer|min:0|max:100',
        ];
    }
}
