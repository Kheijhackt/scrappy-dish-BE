<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,
            'description' => $this->description,

            'ingredients_used' => $this->ingredients_used,
            'steps' => $this->steps,

            'cook_time_minutes' => $this->cook_time_minutes,
            'difficulty' => $this->difficulty,
            'servings' => $this->servings,

            'cuisine_tags' => $this->cuisine_tags,
            'dish_tags' => $this->dish_tags,
            'general_tags' => $this->general_tags,

            'nutrition_notes' => $this->nutrition_notes,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
