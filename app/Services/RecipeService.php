<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\User;

class RecipeService
{
  public function saveRecipe(User $user, array $data): Recipe
  {
    return $user->recipes()->create([
      'title' => $data['title'],
      'description' => $data['description'],
      'ingredients_used' => $data['ingredients_used'],
      'steps' => $data['steps'],
      'cook_time_minutes' => $data['cook_time_minutes'],
      'difficulty' => $data['difficulty'],
      'servings' => $data['servings'],
      'cuisine_tags' => $data['cuisine_tags'],
      'dish_tags' => $data['dish_tags'],
      'general_tags' => $data['general_tags'],
      'nutrition_notes' => $data['nutrition_notes'],
    ]);
  }

  public function getRecipeById(User $user, int $id): Recipe
  {
    return $user->recipes()->findOrFail($id);
  }
}