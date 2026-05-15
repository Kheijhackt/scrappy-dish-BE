<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferencesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'available_ingredients' => $this->available_ingredients,
            'dietary_preferences' => $this->dietary_preferences,
            'cuisine_preferences' => $this->cuisine_preferences,
            'dish_preferences' => $this->dish_preferences,
            'available_equipments' => $this->available_equipments,
        ];
    }
}
