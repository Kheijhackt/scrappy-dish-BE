<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SuggestRecipeTest extends TestCase
{
    public function test_recipe_endpoint_returns_success_response()
    {
        Http::fake([
            'hermes.ai.unturf.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                "title" => "Keto Chicken Salad",
                                "description" => "Healthy keto salad",
                                "ingredients_used" => [
                                    "chicken breast",
                                    "spinach",
                                    "avocado"
                                ],
                                "steps" => [
                                    "Cook chicken",
                                    "Mix ingredients",
                                    "Serve"
                                ],
                                "cook_time_minutes" => 10,
                                "difficulty" => 1,
                                "servings" => 1,
                                "cuisine_tags" => ["Mediterranean"],
                                "dish_tags" => ["lunch"],
                                "general_tags" => ["keto"],
                                "nutrition_notes" => "Low carb and high fat"
                            ])
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/recipe/suggest', [
            "available_ingredients" => ["chicken breast", "spinach", "avocado"],
            "dietary_preferences" => ["keto"],
            "cuisine_preferences" => ["Mediterranean"],
            "dish_preferences" => ["lunch"],
            "available_equipments" => ["knife", "bowl"],
            "cook_time_minutes" => 10,
            "difficulty" => 1,
            "servings" => 1,
            "additional_instructions" => "Make it keto"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "success",
            "message",
            "data" => [
                "title",
                "description",
                "ingredients_used",
                "steps",
                "cook_time_minutes",
                "difficulty",
                "servings",
                "cuisine_tags",
                "dish_tags",
                "general_tags",
                "nutrition_notes"
            ]
        ]);
    }

    public function test_recipe_endpoint_returns_error_response_due_to_bad_request()
    {
        Http::fake([
            'hermes.ai.unturf.com/*' => Http::response([
                'choices' => []
            ], 422)
        ]);

        $response = $this->postJson('/api/recipe/suggest', [
            "available_ingredients" => [],
            "dietary_preferences" => ["keto"],
            "cuisine_preferences" => ["Mediterranean"],
            "dish_preferences" => ["lunch"],
            "available_equipments" => ["knife", "bowl"],
            "cook_time_minutes" => 10,
            "difficulty" => 1,
            "servings" => 1,
            "additional_instructions" => "Make it keto"
        ]);

        $response->assertStatus(422);
    }
}