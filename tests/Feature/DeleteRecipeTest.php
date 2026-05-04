<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Recipe;

uses(RefreshDatabase::class);

test('auth user can delete a recipe', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'id' => $recipe->id
        ]
    ]);
    $this->assertDatabaseCount('recipes', 0);
});

test('auth user cannot delete a recipe that does not belong to them', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => User::factory()->create()->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(422);
    $this->assertDatabaseCount('recipes', 1);
});

test('unauth user cannot delete a recipe', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $user->id]);
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(401);
    $this->assertDatabaseCount('recipes', 1);
});