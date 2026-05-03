<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Recipe;

/**
 * @extends Factory<Recipe>
 */

class RecipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),

            'ingredients_used' => [
                'egg',
                'rice',
                'chicken',
            ],

            'steps' => [
                'Cook rice',
                'Cook chicken',
                'Mix together',
            ],

            'cook_time_minutes' => fake()->numberBetween(5, 60),
            'difficulty' => fake()->numberBetween(1, 5),
            'servings' => fake()->numberBetween(1, 6),

            'cuisine_tags' => ['asian'],
            'dish_tags' => ['main course'],
            'general_tags' => ['easy'],

            'nutrition_notes' => fake()->sentence(),
        ];
    }
}