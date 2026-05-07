<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

test('auth user can get current user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson('/api/user');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data'
    ]);
    $this->assertTrue($response['data']['id'] == $user->id);
});

test('auth user cannot get any other user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson('/api/user/' . $otherUser->id);

    $response->assertStatus(404);
});

test('unauth user cannot get current user', function () {
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json'
    ])->getJson('/api/user');

    $response->assertStatus(401);
});