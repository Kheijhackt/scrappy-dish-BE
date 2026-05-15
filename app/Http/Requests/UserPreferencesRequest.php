<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserPreferencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'available_ingredients' => ['sometimes', 'array', 'min:1'],
            'available_ingredients.*' => ['string'],

            'dietary_preferences' => ['sometimes', 'array'],
            'dietary_preferences.*' => ['string'],

            'cuisine_preferences' => ['sometimes', 'array'],
            'cuisine_preferences.*' => ['string'],

            'dish_preferences' => ['sometimes', 'array'],
            'dish_preferences.*' => ['string'],

            'available_equipments' => ['sometimes', 'array'],
            'available_equipments.*' => ['string'],
        ];
    }
}
