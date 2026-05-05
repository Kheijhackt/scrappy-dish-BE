<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;


uses(RefreshDatabase::class);

test('auth user can logout and deletes the currently owned token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;


    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->getJson('/api/logout');

    $response->assertStatus(200);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('unauth user cannot logout', function () {
    $user = User::factory()->create();
    $user->createToken('test-token')->plainTextToken;
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->getJson('/api/logout');

    $response->assertStatus(401);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('personal_access_tokens', 1);
});

test('auth user can logout all logged in sessions and deletes all owned tokens', function () {
    $user = User::factory()->create();
    
    $tokens = collect(range(1, 10))
        ->map(fn() => $user->createToken('test-token')->plainTextToken)
        ->all();

    $authToken = fake()->randomElement($tokens);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $authToken
    ])->getJson('/api/logout-all');

    $response->assertStatus(200);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('unauth user cannot logout all logged in sessions', function () {
    $user = User::factory()->create();
    
    $tokens = collect(range(1, 10))
        ->map(fn() => $user->createToken('test-token')->plainTextToken)
        ->all();

    $authToken = fake()->randomElement($tokens);
    $currentToken = 'invalid-token';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $currentToken
    ])->getJson('/api/logout-all');

    $response->assertStatus(401);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('personal_access_tokens', 10);
});