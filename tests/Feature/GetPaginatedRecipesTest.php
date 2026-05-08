<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('auth user can retrieve paginated recipes', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;
    Recipe::factory(10)->create(['user_id' => $user->id]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->getJson('/api/recipes'.'?page=1&per_page=15');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data',
    ]);
});

test('auth user cannot retrieve paginated recipes that do not belong to them', function () {
    $user = User::factory()->create();
    Recipe::factory(10)->create(['user_id' => User::factory()->create()->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->getJson('/api/recipes'.'?page=1&per_page=15');

    $response->assertJson(['message' => 'No recipes found']);
});

test('unauth user cannot retrieve paginated recipes', function () {
    $user = User::factory()->create();
    Recipe::factory(10)->create(['user_id' => $user->id]);
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->getJson('/api/recipes'.'?page=1&per_page=15');

    $response->assertStatus(401);
});
