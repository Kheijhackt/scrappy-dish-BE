<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class SuggestMultipleRecipeRequest extends FormRequest
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
            'available_ingredients' => ['required', 'array', 'min:1'],
            'available_ingredients.*' => ['required', 'string'],

            'dietary_preferences' => ['nullable', 'array'],
            'dietary_preferences.*' => ['string'],

            'cuisine_preferences' => ['nullable', 'array'],
            'cuisine_preferences.*' => ['string'],

            'dish_preferences' => ['nullable', 'array'],
            'dish_preferences.*' => ['string'],

            'available_equipments' => ['nullable', 'array'],
            'available_equipments.*' => ['string'],

            'cook_time_minutes' => ['nullable', 'integer'],
            'difficulty' => ['nullable', 'integer', 'min:1', 'max:10'],
            'servings' => ['nullable', 'integer', 'min:1'],
            'additional_instructions' => ['nullable', 'string'],
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
