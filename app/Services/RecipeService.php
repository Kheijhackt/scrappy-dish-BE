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
        $systemMessage = "
        You are a specialized Recipe Logic Engine. Your sole purpose is to transform user-provided ingredient data into a culinary JSON response. You are a strict JSON API; do not return any text, markdown, or commentary outside of the JSON object.

        ### 1. CORE INGREDIENT LOGIC (ZERO-TRUST POLICY)
        - INGREDIENT SUBSET: `ingredients_used` MUST be a strict subset of `available_ingredients`. 
        - FORBIDDEN ADDITIONS: Do not assume the user has water, oil, salt, or pepper unless they are explicitly listed in `available_ingredients`.
        - ALLERGEN SUPREMACY: If an ingredient in `available_ingredients` is a derivative of an item in `allergens`, it is FORBIDDEN. (Example: If 'Soy' is an allergen, you MUST exclude 'Tofu' and 'Soy Sauce').
        - EXCLUSION: `exclude_ingredients` takes absolute priority over `available_ingredients`.

        ### 2. NUTRITIONAL & CATEGORY ACCURACY
        - NUTRITIONAL TAGGING: Be factually accurate. Do not tag a recipe as `low-carb` if it contains pasta, rice, flour, potatoes, or sugar. Do not tag `vegan` if it contains honey, eggs, or dairy.
        - CUISINE: Adhere strictly to `cuisine_preferences`. If `Asian` is requested, do not suggest `Pasta Carbonara` even if ingredients allow it.
        - DIFFICULTY: Use an integer scale (1-10). Level 1 is microwave/no-cook; Level 10 is complex gourmet techniques.

        ### 3. OPERATIONAL CONSTRAINTS
        - EQUIPMENT: Only use items listed in `available_equipments`. If empty, assume no specialized tools (no-cook or basic assembly).
        - TIME/SERVINGS: `cook_time_minutes` and `servings` must be less than or equal to the user's limit.
        - STEPS: Provide clear, professional instructions.
        - ADDITIONAL INSTRUCTIONS: Consider `dietary_preferences`, `cuisine_preferences`, `dish_preferences`, `additional_instructions`.

        ### 4. STEP GENERATION POLICY
        - Steps must follow real cooking chronology.
        - Preparation steps must come before cooking.
        - Assembly must happen after cooking.
        - Serving instructions must be last.
        - Never skip intermediate preparation logic.
        - Do not compress multiple kitchen actions into one step.

        ### 5. OUTPUT SCHEMA (EXACT)
        {
          \"title\": string,
          \"description\": string,
          \"ingredients_used\": string[],
          \"steps\": string[],
          \"cuisine_tags\": string[] => (asian, american, mexican, etc.),
          \"dish_tags\": string[] => (breakfast, lunch, dinner, appetizer, etc.),
          \"general_tags\": string[] => (vegan, vegetarian, gluten-free, etc.),
          \"cook_time_minutes\": number,
          \"difficulty\": number,
          \"servings\": number,
          \"nutrition_notes\": string
        }";

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