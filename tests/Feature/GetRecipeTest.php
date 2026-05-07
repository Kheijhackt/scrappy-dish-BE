<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Recipe;

uses(RefreshDatabase::class);

test('auth user can retrieve one recipe that exists', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $user->id]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data'
    ]);
    $this->assertTrue($response['data']['id'] == $recipe->id);
});

test('auth user cannot retrieve one recipe that does not exist', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson('/api/recipes/1');

    $response->assertStatus(422);
    $this->assertDatabaseCount('recipes', 0);
});

test('auth user cannot retrieve one recipe that does not belong to them', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => User::factory()->create()->id]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(422);
});

test('unauth user cannot retrieve one recipe', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $user->id]);

    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson('/api/recipes/' . $recipe->id);

    $response->assertStatus(401);
});