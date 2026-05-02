<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait CallsRecipeAI
{
    protected function callRecipeAI(
        string $systemMessage,
        string $prompt,
        array $options = []
    ): array {
        $timeout = $options['timeout'] ?? 120;
        $response = Http::timeout($timeout)->post('https://hermes.ai.unturf.com/v1/chat/completions', [
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
            "temperature" => $options['temperature'] ?? 0.1,
            "max_tokens" => $options['max_tokens'] ?? 1000,
            "top_p" => $options['top_p'] ?? 0.9,
            "frequency_penalty" => $options['frequency_penalty'] ?? 0,
            "presence_penalty" => $options['presence_penalty'] ?? 0,
            "stream" => false
        ]);

        $content = $response->json('choices.0.message.content');
        $decoded = json_decode($content, true);
        $decoded['created_epoch'] = $response->json('created');

        return $decoded;
    }
}