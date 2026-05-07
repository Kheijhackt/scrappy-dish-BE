<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Models\User;

uses(RefreshDatabase::class);

test('new user successfully signed up through google and is given a token', function () {
    Http::fake([
        'https://identitytoolkit.googleapis.com/*' => Http::response([
            'users' => [
                [
                    'localId' => 'firebase_uid_123',
                    'email' => 'testuser@gmail.com',
                    'providerUserInfo' => [
                        [
                            'providerId' => 'google.com',
                            'rawId' => '100200300400500', 
                            'email' => 'testuser@gmail.com', 
                            'displayName' => 'Test User',  
                            'photoUrl' => 'https://lh3.googleusercontent.com/a/test'
                        ]
                    ]
                ]
            ]
        ], 200),
    ]);

    $response = $this->postJson('/api/auth/google', [
       'id_token' => 'valid_id_token'
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'user' => [
                'name',
                'email',
            ],
            'token'
        ]
    ]);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('personal_access_tokens', 1);
});

test('existing user successfully signed up through google and is given a token', function () {
    $user = User::factory()->create();
    $user->createToken('test-token')->plainTextToken; // this indicates that the user logged in before
    Http::fake([
        'https://identitytoolkit.googleapis.com/*' => Http::response([
            'users' => [
                [
                    'localId' => $user->google_id,
                    'email' => $user->email,
                    'providerUserInfo' => [
                        [
                            'providerId' => 'google.com',
                            'rawId' => $user->google_id, 
                            'email' => $user->email, 
                            'displayName' => $user->name,  
                            'photoUrl' => $user->avatar
                        ]
                    ]
                ]
            ]
        ], 200),
    ]);

    $response = $this->postJson('/api/auth/google', [
       'id_token' => 'valid_id_token'
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'user' => [
                'name',
                'email',
            ],
            'token'
        ]
    ]);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('personal_access_tokens', 2);
});

test('user cannot sign up or login with invalid google id token', function () {
    $response = $this->postJson('/api/auth/google', [
       'id_token' => 'invalid_id_token'
    ]);

    $response->assertStatus(422);
    $response->assertJsonStructure([
        'success',
        'message',
        'data'
    ]);
    $this->assertDatabaseCount('users', 0);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});