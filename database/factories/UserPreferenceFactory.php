<?php

namespace Database\Factories;

use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'available_ingredients' => ['salt', 'pepper', 'water', 'oil'],
            'dietary_preferences' => ['keto'],
            'cuisine_preferences' => ['Mediterranean'],
            'dish_preferences' => ['lunch'],
            'available_equipments' => ['knife', 'bowl'],
        ];
    }
}
