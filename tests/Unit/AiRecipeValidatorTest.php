<?php

namespace Tests\Unit;

use App\Services\AiRecipeValidator;
use Tests\TestCase;

class AiRecipeValidatorTest extends TestCase
{
    public function test_valid_ai_response_passes()
    {
        $validator = new AiRecipeValidator();

        $request = [
            "available_ingredients" => ["egg", "rice", "soy sauce"]
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
            "nutrition_notes" => "balanced"
        ];

        $result = $validator->isValid($data, $request);

        $this->assertTrue($result['valid']);
        $this->assertEquals("AI response is valid", $result['message']);
    }

    public function test_invalid_ingredient_fails_validation()
    {
        $validator = new AiRecipeValidator();

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
            "nutrition_notes" => "balanced"
        ];

        $result = $validator->isValid($data, $request);

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString("not in available ingredients", $result['message']);
    }
}