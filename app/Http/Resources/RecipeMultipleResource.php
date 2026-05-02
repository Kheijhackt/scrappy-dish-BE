<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeMultipleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

      
    protected bool $success;
    protected string $message;

    public function __construct($resource, bool $success, string $message)
    {
        parent::__construct($resource);
        $this->success = $success;
        $this->message = $message;
    }
    public function toArray(Request $request): array
    {
        if(!$this->success) {
            return [
                'success' => $this->success,
                'message' => $this->message,
                'data' => []
            ];
        }

        return [
            'success' => $this->success,
            'message' => $this->message,

            'data' => [
                'created_epoch' => $this['created_epoch'],

                'recipes' => collect($this['recipes'])->map(function ($recipe) {
                    return [
                        'title' => $recipe['title'],
                        'description' => $recipe['description'],
                        'ingredients_used' => $recipe['ingredients_used'],
                        'steps' => $recipe['steps'],
                        'cook_time_minutes' => $recipe['cook_time_minutes'],
                        'difficulty' => $recipe['difficulty'],
                        'servings' => $recipe['servings'],
                        'cuisine_tags' => $recipe['cuisine_tags'],
                        'dish_tags' => $recipe['dish_tags'],
                        'general_tags' => $recipe['general_tags'],
                        'nutrition_notes' => $recipe['nutrition_notes'],
                    ];
                })->values(),
            ]
        ];
    }
}
