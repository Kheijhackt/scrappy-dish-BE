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
            \"missing_ingredients\": string[],
            \"steps\": string[],
            \"cook_time_minutes\": number,
            \"difficulty\": number (1-10),
            \"servings\": number,
            \"cuisine\": string,
            \"tags\": string[],
            \"nutrition_notes\": string
          }

          Rules:
          - difficulty MUST be integer 1–10 only
          - steps must be clear instructions
          - ingredients_used must only come from user input
          - return ONLY JSON";

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
            "temperature" => 0.4,
            "max_tokens" => 500,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0,
            "stream" => false
        ]);

        $content = $response->json('choices.0.message.content');
        $decoded = json_decode($content, true);

        if(!$this->validator->isValid($decoded)) {
            throw new \Exception('Invalid response from AI');
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