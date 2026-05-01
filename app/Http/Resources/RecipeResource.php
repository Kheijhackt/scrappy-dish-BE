<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     *
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
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => [
                'title' => $this['title'],
                'description' => $this['description'],
                'ingredients_used' => $this['ingredients_used'],
                'steps' => $this['steps'],
                'cook_time_minutes' => $this['cook_time_minutes'],
                'difficulty' => $this['difficulty'],
                'servings' => $this['servings'],
                'cuisine' => $this['cuisine'],
                'dish_type' => $this['dish_type'],
                'tags' => $this['tags'],
                'nutrition_notes' => $this['nutrition_notes'],
            ]
        ];
    }
}
