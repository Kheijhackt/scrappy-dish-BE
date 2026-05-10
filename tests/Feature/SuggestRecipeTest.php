<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('auth user can retrieve one AI suggested recipe', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    Http::fake([
        'hermes.ai.unturf.com/*' => Http::response([
            'choices' => [
                [
                    'message' => [
                        'content' => json_encode([
                            'title' => 'Test title',
                            'description' => 'Sample description',
                            'ingredients_used' => [],
                            'steps' => [
                                'Cook chicken',
                                'Mix ingredients',
                                'Serve',
                            ],
                            'cook_time_minutes' => 5,
                            'difficulty' => 1,
                            'servings' => 1,
                            'cuisine_tags' => ['Mediterranean'],
                            'dish_tags' => ['lunch'],
                            'general_tags' => ['keto'],
                            'nutrition_notes' => 'Low carb and high fat',
                            'created_epoch' => 1777707148,
                        ]),
                    ],
                ],
            ],
        ], 200),
    ]);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/recipes/suggest-single');

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'title',
            'description',
            'ingredients_used',
            'steps',
            'cook_time_minutes',
            'difficulty',
            'servings',
            'cuisine_tags',
            'dish_tags',
            'general_tags',
            'nutrition_notes',
            'created_epoch',
        ],
    ]);

}
);

test('unauth user cannot retrieve one AI suggested recipe', function () {
    Http::fake([
        'hermes.ai.unturf.com/*' => Http::response([
            'choices' => [
                [
                    'message' => [
                        'content' => json_encode([
                            'title' => 'Test title',
                            'description' => 'Sample description',
                            'ingredients_used' => [],
                            'steps' => [
                                'Cook chicken',
                                'Mix ingredients',
                                'Serve',
                            ],
                            'cook_time_minutes' => 5,
                            'difficulty' => 1,
                            'servings' => 1,
                            'cuisine_tags' => ['Mediterranean'],
                            'dish_tags' => ['lunch'],
                            'general_tags' => ['keto'],
                            'nutrition_notes' => 'Low carb and high fat',
                            'created_epoch' => 1777707148,
                        ]),
                    ],
                ],
            ],
        ], 200),
    ]);

    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $token = 'invalid-token';

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$token,
    ])->getJson('/api/recipes/suggest-single');

    $response->assertStatus(401);
}
);

test('auth user can retrieve multiple AI suggested recipes', function () {
    Http::fake([
        'hermes.ai.unturf.com/*' => Http::response([
            'choices' => [
                [
                    'message' => [
                        'content' => json_encode([
                            'created_epoch' => 1777707148,
                            'recipes' => [
                                [
                                    'title' => 'Keto Chicken Salad',
                                    'description' => 'Healthy keto salad',
                                    'ingredients_used' => [],
                                    'steps' => [
                                        'Cook chicken',
                                        'Mix ingredients',
                                        'Serve',
                                    ],
                                    'cook_time_minutes' => 10,
                                    'difficulty' => 1,
                                    'servings' => 1,
                                    'cuisine_tags' => ['Mediterranean'],
                                    'dish_tags' => ['lunch'],
                                    'general_tags' => ['keto'],
                                    'nutrition_notes' => 'Low carb and high fat',
                                ],
                                [
                                    'title' => 'Keto Chicken Salad',
                                    'description' => 'Healthy keto salad',
                                    'ingredients_used' => [
                                    ],
                                    'steps' => [
                                        'Cook chicken',
                                        'Mix ingredients',
                                        'Serve',
                                    ],
                                    'cook_time_minutes' => 10,
                                    'difficulty' => 1,
                                    'servings' => 1,
                                    'cuisine_tags' => ['Mediterranean'],
                                    'dish_tags' => ['lunch'],
                                    'general_tags' => ['keto'],
                                    'nutrition_notes' => 'Low carb and high fat', ],
                            ],
                        ]),
                    ],
                ],
            ],
        ], 200),
    ]);

    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $payload = [
        'cook_time_minutes' => 10,
        'difficulty' => 1,
        'servings' => 1,
        'additional_instructions' => 'Make it keto',
    ];

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/recipes/suggest-multiple', $payload);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'created_epoch',
            'recipes' => [
                [
                    'title',
                    'description',
                    'ingredients_used',
                    'steps',
                    'cook_time_minutes',
                    'difficulty',
                    'servings',
                    'cuisine_tags',
                    'dish_tags',
                    'general_tags',
                    'nutrition_notes',
                ],
                [
                    'title',
                    'description',
                    'ingredients_used',
                    'steps',
                    'cook_time_minutes',
                    'difficulty',
                    'servings',
                    'cuisine_tags',
                    'dish_tags',
                    'general_tags',
                    'nutrition_notes',
                ],
            ],
        ],
    ]);
}
);

test('auth user cannot retrieve multiple AI suggested recipes due to bad request', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $payload = [
        'cook_time_minutes' => 10,
        'difficulty' => 1,
        'servings' => 'invalid',
        'additional_instructions' => 'Make it keto',
    ];

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/recipes/suggest-multiple', $payload);

    $response->assertStatus(422);
});

test('unauth user cannot retrieve multiple AI suggested recipes', function () {
    Http::fake([
        'hermes.ai.unturf.com/*' => Http::response([
            'choices' => [
                [
                    'message' => [
                        'content' => json_encode([
                            'title' => 'Test title',
                            'description' => 'Sample description',
                            'ingredients_used' => [],
                            'steps' => [
                                'Cook chicken',
                                'Mix ingredients',
                                'Serve',
                            ],
                            'cook_time_minutes' => 5,
                            'difficulty' => 1,
                            'servings' => 1,
                            'cuisine_tags' => ['Mediterranean'],
                            'dish_tags' => ['lunch'],
                            'general_tags' => ['keto'],
                            'nutrition_notes' => 'Low carb and high fat',
                            'created_epoch' => 1777707148,
                        ]),
                    ],
                ],
            ],
        ], 200),
    ]);

    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;
    $token = 'invalid-token';

    $payload = [
        'cook_time_minutes' => 10,
        'difficulty' => 1,
        'servings' => 'invalid',
        'additional_instructions' => 'Make it keto',
    ];

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/recipes/suggest-multiple', $payload);

    $response->assertStatus(401);
}
);
