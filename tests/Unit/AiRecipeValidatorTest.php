<?php

namespace Tests\Unit;

use App\Services\AiResponseRecipeValidator;
use Tests\TestCase;

class AiRecipeValidatorTest extends TestCase
{
    public function test_valid_ai_responses_passes_single()
    {
        $validator = new AiResponseRecipeValidator();

        $request = [
            "available_ingredients" => ["egg", "rice"],
            "available_equipments" => ["knife", "bowl"]
        ];

        $data = [
            "title" => "Fried Rice",
            "description" => "Simple fried rice",
            "ingredients_used" => ["egg", "rice"],
            "steps" => ["Cook rice", "Fry egg"],
            "cook_time_minutes" => 10,
            "difficulty" => 1,
            "servings" => 1,
            "general_tags" => ["easy"],
            "cuisine_tags" => ["asian"],
            "dish_tags" => ["lunch"],
            "nutrition_notes" => "balanced",
            "created_epoch" => 1777707148
        ];

        $result = $validator->isValidSingle($data, $request);

        $this->assertTrue($result['valid']);
    }

    public function test_invalid_ingredient_fails_validation_single()
    {
        $validator = new AiResponseRecipeValidator();

        $request = [
            "available_ingredients" => ["egg", "rice"]
        ];

        $data = [
            "title" => "Fried Rice",
            "description" => "Simple fried rice",
            "ingredients_used" => ["egg", "rice", "chicken"],
            "steps" => ["Cook"],
            "cook_time_minutes" => 10,
            "difficulty" => 1,
            "servings" => 1,
            "general_tags" => ["easy"],
            "cuisine_tags" => ["asian"],
            "dish_tags" => ["lunch"],
            "nutrition_notes" => "balanced",
            "created_epoch" => 1777707148
        ];

        $result = $validator->isValidSingle($data, $request);

        $this->assertFalse($result['valid']);
    }

    public function test_valid_ai_responses_passes_multiple()
    {
        $validator = new AiResponseRecipeValidator();

        $request = [
            "available_ingredients" => ["egg", "rice"],
            "available_equipments" => ["knife", "bowl"]
        ];

        $data = [
            "recipes" => [
                [
                    "title" => "Fried Rice",
                    "description" => "Simple fried rice",
                    "ingredients_used" => ["egg", "rice"],
                    "steps" => ["Cook rice", "Fry egg"],
                    "cook_time_minutes" => 10,
                    "difficulty" => 1,
                    "servings" => 1,
                    "general_tags" => ["easy"],
                    "cuisine_tags" => ["asian"],
                    "dish_tags" => ["lunch"],
                    "nutrition_notes" => "balanced",
                ],
                [
                    "title" => "Fried Rice",
                    "description" => "Simple fried rice",
                    "ingredients_used" => ["egg", "rice"],
                    "steps" => ["Cook rice", "Fry egg"],
                    "cook_time_minutes" => 10,
                    "difficulty" => 1,
                    "servings" => 1,
                    "general_tags" => ["easy"],
                    "cuisine_tags" => ["asian"],
                    "dish_tags" => ["lunch"],
                    "nutrition_notes" => "balanced",
                ]
            ],
            "created_epoch" => 1777707148
        ];

        $result = $validator->isValidMultiple($data, $request);

        $this->assertTrue($result['valid']);
    }

    public function test_invalid_ingredient_fails_validation_multiple()
    {
        $validator = new AiResponseRecipeValidator();

        $request = [
            "available_ingredients" => ["egg", "rice"],
            "available_equipments" => ["knife", "bowl"]
        ];

        $data = [
            "recipes" => [
                [
                    "title" => "Fried Rice",
                    "description" => "Simple fried rice",
                    "ingredients_used" => ["egg", "rice", "chicken"],
                    "steps" => ["Cook rice", "Fry egg"],
                    "cook_time_minutes" => 10,
                    "difficulty" => 1,
                    "servings" => 1,
                    "general_tags" => ["easy"],
                    "cuisine_tags" => ["asian"],
                    "dish_tags" => ["lunch"],
                    "nutrition_notes" => "balanced",
                ],
                [
                    "title" => "Fried Rice",
                    "description" => "Simple fried rice",
                    "ingredients_used" => ["egg", "rice"],
                    "steps" => ["Cook rice", "Fry egg"],
                    "cook_time_minutes" => 10,
                    "difficulty" => 1,
                    "servings" => 1,
                    "general_tags" => ["easy"],
                    "cuisine_tags" => ["asian"],
                    "dish_tags" => ["lunch"],
                    "nutrition_notes" => "balanced",
                ]
            ],
            "created_epoch" => 1777707148
        ];

        $result = $validator->isValidMultiple($data, $request);

        $this->assertFalse($result['valid']);
    }
}