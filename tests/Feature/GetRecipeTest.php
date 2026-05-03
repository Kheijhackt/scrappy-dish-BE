<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Recipe;

uses(RefreshDatabase::class);

test('auth user can retrieve one recipe', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $user->id]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data'
    ]);
});

test('auth user cannot retrieve one recipe that does not belong to them', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => User::factory()->create()->id]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(500);
});

test('unauth user cannot retrieve one recipe', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $user->id]);

    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(401);
});

