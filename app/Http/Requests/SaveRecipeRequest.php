<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class SaveRecipeRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],

            'ingredients_used' => ['required', 'array', 'min:1'],
            'ingredients_used.*' => ['string'],

            'steps' => ['required', 'array', 'min:1'],
            'steps.*' => ['string'],

            'cook_time_minutes' => ['required', 'integer', 'min:1'],
            'difficulty' => ['required', 'integer', 'between:1,10'],
            'servings' => ['required', 'integer', 'min:1'],

            'cuisine_tags' => ['sometimes', 'array'],
            'cuisine_tags.*' => ['string'],

            'dish_tags' => ['sometimes', 'array'],
            'dish_tags.*' => ['string'],

            'general_tags' => ['sometimes', 'array'],
            'general_tags.*' => ['string'],

            'nutrition_notes' => ['nullable', 'string'],
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
