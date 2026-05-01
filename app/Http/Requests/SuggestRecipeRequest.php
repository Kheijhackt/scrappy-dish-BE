<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class SuggestRecipeRequest extends FormRequest
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
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*' => ['required', 'string'],
            'allergens' => ['nullable', 'array'],
            'allergens.*' => ['string'],
            'dietary_preferences' => ['nullable', 'array'],
            'dietary_preferences.*' => ['string'],
            'cuisine_preference' => ['nullable', 'string'],
            'time_limit_minutes' => ['nullable', 'integer'],
            'difficulty' => ['nullable', 'integer'],
            'servings' => ['nullable', 'integer'],
            'available_equipment' => ['nullable', 'array'],
            'available_equipment.*' => ['string'],
            'exclude_ingredients' => ['nullable', 'array'],
            'exclude_ingredients.*' => ['string'],
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
