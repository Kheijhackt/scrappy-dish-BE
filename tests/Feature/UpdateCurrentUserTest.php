<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
uses(RefreshDatabase::class);

test('auth user can update current user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->patchJson('/api/user', [
        'name' => 'Updated name',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data'
    ]);
    $this->assertTrue($response['data']['name'] == 'Updated name');
});

test('auth user cannot update current user due to request validation error', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->patchJson('/api/user', [
        'name' => '',
    ]);

    $response->assertStatus(422);
});

test('unauth user cannot update current user', function () {
    $user = User::factory()->create();
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->patchJson('/api/user', [
        'name' => 'Updated name',
    ]);

    $response->assertStatus(401);
});