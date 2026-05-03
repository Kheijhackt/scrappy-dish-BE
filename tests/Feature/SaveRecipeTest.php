<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('user can save a recipe', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $payload = [
        'title' => 'Test Recipe',
        'description' => 'A test recipe description',
        'ingredients_used' => ['Ingredient 1', 'Ingredient 2'],
        'steps' => ['Step 1', 'Step 2'],
        'cook_time_minutes' => 30,
        'difficulty' => 5,
        'servings' => 4,
        'cuisine_tags' => ['Cuisine 1', 'Cuisine 2'],
        'dish_tags' => ['Dish 1', 'Dish 2'],
        'general_tags' => ['Tag 1', 'Tag 2'],
        'nutrition_notes' => 'Some nutrition notes',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/recipes/save', $payload);

    $response->assertStatus(200);
    $this->assertDatabaseHas('recipes', [
        'title' => $payload['title'],
        'description' => $payload['description'],
        'ingredients_used' => json_encode($payload['ingredients_used']),
        'steps' => json_encode($payload['steps']),
        'cook_time_minutes' => $payload['cook_time_minutes'],
        'difficulty' => $payload['difficulty'],
        'servings' => $payload['servings'],
        'cuisine_tags' => json_encode($payload['cuisine_tags']),
        'dish_tags' => json_encode($payload['dish_tags']),
        'general_tags' => json_encode($payload['general_tags']),
        'nutrition_notes' => $payload['nutrition_notes'],
        'user_id' => $user->id
    ]);
});

test('unauthenticated user cannot save a recipe', function () {
    $user = User::factory()->create();
    $token = 'invalid-token';

    $payload = [
        'title' => 'Test Recipe',
        'description' => 'A test recipe description',
        'ingredients_used' => ['Ingredient 1', 'Ingredient 2'],
        'steps' => ['Step 1', 'Step 2'],
        'cook_time_minutes' => 30,
        'difficulty' => 5,
        'servings' => 4,
        'cuisine_tags' => ['Cuisine 1', 'Cuisine 2'],
        'dish_tags' => ['Dish 1', 'Dish 2'],
        'general_tags' => ['Tag 1', 'Tag 2'],
        'nutrition_notes' => 'Some nutrition notes',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/recipes/save', $payload);

    $response->assertStatus(401);
});

test('user submits a recipe with invalid data', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $payload = [
        'title' => 'Test Recipe',
        'description' => 'A test recipe description',
        'ingredients_used' => ['Ingredient 1', 'Ingredient 2'],
        'steps' => ['Step 1', 'Step 2'],
        'cook_time_minutes' => 30,
        'difficulty' => "invalid",
        'servings' => 4,
        'cuisine_tags' => ['Cuisine 1', 'Cuisine 2'],
        'dish_tags' => ['Dish 1', 'Dish 2'],
        'general_tags' => ['Tag 1', 'Tag 2'],
        'nutrition_notes' => 'Some nutrition notes',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/recipes/save', $payload);

    $response->assertStatus(422);
});
