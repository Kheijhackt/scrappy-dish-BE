<?php

namespace App\Services;


class AiResponseRecipeValidator
{
  public function isValidSingle(array $data, array $request): array
  {
    $logName = "AI Response Recipe Validator (Single): ";
    $response = [
      'valid' => true,
      'message' => 'AI response is valid'
    ];

    if(!is_array($data)) {
      $response = [
        'valid' => false,
        'message' => $logName . 'Data must be an array'
      ];

      return $response;
    }
    
    $requiredStrings = [
      'title',
      'description',
      'nutrition_notes'
    ];

    $requiredArrays = [
      'ingredients_used',
      'steps',
      'general_tags',
      'cuisine_tags',
      'dish_tags'
    ];

    $requiredInts = [
      'cook_time_minutes',
      'difficulty',
      'servings'
    ];

    foreach($requiredStrings as $field) {
      if(!isset($data[$field]) || !is_string($data[$field])) {
        $response = [
          'valid' => false,
          'message' => $logName . $field . ' is required and must be a string'
        ];

        return $response;
      }
    }

    foreach($requiredArrays as $field) {
      if(!isset($data[$field]) || !is_array($data[$field])) {
        $response = [
          'valid' => false,
          'message' => $logName . $field . ' is required and must be an array'
        ];

        return $response;
      }
    }

    foreach($requiredInts as $field) {
      if(!isset($data[$field]) || !is_int($data[$field])) {
        $response = [
          'valid' => false,
          'message' => $logName . $field . ' is required and must be an integer'
        ];

        return $response;
      }
    }

    foreach($data['ingredients_used'] as $ingredient) {
      if(!in_array($ingredient, $request['available_ingredients'])) {
        $response = [
          'valid' => false,
          'message' => $logName . 'Ingredient ' . $ingredient . ' is not in available ingredients'
        ];

        return $response;
      }
    }

    if($data['difficulty'] < 1 || $data['difficulty'] > 10) {
      $response = [
        'valid' => false,
        'message' => $logName . 'Difficulty must be between 1 and 10'
      ];

      return $response;
    }

    return $response;
  }

  public function isValidMultiple(array $data, array $request): array
  {
    $logName = "AI Response Recipe Validator (Multiple): ";
    $response = [
      'valid' => true,
      'message' => 'AI response is valid'
    ];

    if(!is_array($data)) {
      $response = [
        'valid' => false,
        'message' => $logName . 'Data must be an array'
      ];

      return $response;
    }

    foreach($data['recipes'] as $recipe) {
      $result = $this->isValidSingle($recipe, $request);
      if(!$result['valid']) {
        $response = $result;
        return $response;
      }
    }

    return $response;
  }
}