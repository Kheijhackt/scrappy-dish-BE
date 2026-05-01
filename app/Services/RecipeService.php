<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\AiRecipeValidator;

class RecipeService
{

    public function __construct(private AiRecipeValidator $validator) {}

    public function generate(array $data)
    {
        $prompt = $this->buildPrompt($data);
        $systemMessage = "You are a recipe generator.
          You are a strict JSON API engine.
          You MUST return ONLY valid JSON.
          Do NOT include explanations, markdown, or extra text.

          Follow this schema EXACTLY:

          {
            \"title\": string,
            \"description\": string,
            \"ingredients_used\": string[],
            \"steps\": string[],
            \"cook_time_minutes\": number,
            \"difficulty\": number (1-10),
            \"servings\": number,
            \"cuisine\": string,
            \"dish_type\": string,
            \"tags\": string[],
            \"nutrition_notes\": string
          }

          Rules of DONT'S:
          - do NOT suggest a recipe that uses ingredients that are not provided from the available_ingredients list from user input
          - do NOT suggest a recipe that uses ingredients from the allergens list from user input (if provided)
          - do NOT suggest a recipe that is not in the cuisine_preferences from user input (if provided)
          - do NOT suggest a recipe that exceeds the time limit from user input (if provided)
          - do NOT suggest a recipe that exceeds the difficulty from user input (if provided)
          - do NOT suggest a recipe that exceeds the servings from user input (if provided)
          - do NOT suggest a recipe that uses equipment that is not provided from the available_equipment list from user input (if provided)
          - do NOT suggest a recipe that uses ingredients that are provided from the exclude_ingredients list from user input (if provided)
          - do NOT suggest a recipe where the dish_type from user input (if provided) is not included
          - do NOT invent extra constraints unless necessary

          Rules of MUST'S:
          - suggest a recipe that uses some or all of the ingredients that are provided from the available_ingredients list from user input
          - suggest a recipe respecting the dietary_preferences list from user input (if provided)
          - suggest a recipe respecting the cuisine_preferences from user input (if provided)
          - suggest a recipe that is shorter or equal to the time_limit_minutes from user input (if provided)
          - suggest a recipe that is easier or equal to the difficulty from user input (if provided)
          - suggest a recipe that is served for the servings from user input (if provided)
          - suggest a recipe that uses some or all equipments that is provided from the available_equipment list from user input (if provided)
          - suggest a recipe that uses ingredients that are not provided from the exclude_ingredients list from user input (if provided)
          - suggest a recipe considering the dish_type from user input (if provided)
          - suggest a recipe that considers the additional_instructions from user input (if provided)

          NOTES:
          - difficulty MUST be integer 1–10 only (1-easiest, 10-hardest)
          - steps must be clear and detailed instructions of how to make the recipe
          
          INGREDIENT RULE:
          - ingredients_used on your response MUST be a SUBSET of available_ingredients from user request
          - NO additional ingredients are allowed under any circumstances
          ";

        $response = Http::post('https://hermes.ai.unturf.com/v1/chat/completions', [
            "model" => "adamo1139/Hermes-3-Llama-3.1-8B-FP8-Dynamic",
            "messages" => [
                [
                    "role" => "system",
                    "content" => $systemMessage
                ],
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ],
            "temperature" => 0.1,
            "max_tokens" => 1000,
            "top_p" => 0.9,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            "stream" => false
        ]);

        $content = $response->json('choices.0.message.content');
        $decoded = json_decode($content, true);

        $validatorResult = $this->validator->isValid($decoded, $data);

        if(!$validatorResult['valid']) {
            throw new \Exception($validatorResult['message']);
        }

        return $decoded;
    }

    private function buildPrompt(array $data): string
    {
        return "Generate recipe suggestions using ONLY this input data:\n\n"
            . json_encode($data, JSON_PRETTY_PRINT)
            . "\n\nReturn STRICT JSON only. Follow system rules exactly.";
    }
}