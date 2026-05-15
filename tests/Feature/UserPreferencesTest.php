<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('auth user can get user preferences', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->get('/api/user-preferences');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data',
    ]);
});

test('unauth user cannot get user preferences', function () {
    $user = User::factory()->create();
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->get('/api/user-preferences');

    $response->assertStatus(401);
});

test('auth user can update user preferences', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->patchJson('/api/user-preferences', [
        'available_ingredients' => ['apple', 'banana'],
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data',
    ]);
});

test('auth user cannot update user preferences due to request validation error', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->patchJson('/api/user-preferences', [
        'available_ingredients' => [],
    ]);

    $response->assertStatus(422);
});

test('unauth user cannot update user preferences', function () {
    $user = User::factory()->create();
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->patchJson('/api/user-preferences', [
        'available_ingredients' => ['apple', 'banana'],
    ]);

    $response->assertStatus(401);
});