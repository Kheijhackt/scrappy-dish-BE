<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('auth user can delete current user with owned recipes and tokens', function () {
    $user = User::factory()->create();
    Recipe::factory(10)->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->deleteJson('/api/user');

    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'id' => $user->id,
        ],
    ]);
    $this->assertDatabaseCount('users', 0);
    $this->assertDatabaseCount('recipes', 0);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('unauth user cannot delete any user', function () {
    $user = User::factory()->create();
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->deleteJson('/api/user');

    $response->assertStatus(401);
});
