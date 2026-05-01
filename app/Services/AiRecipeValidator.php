<?php

namespace App\Services;

class AiRecipeValidator
{
  public function isValid(array $data): bool
  {
    if(!is_array($data)) {
      return false;
    }
    
    $requiredStrings = [
      'title',
      'description',
      'cuisine',
      'nutrition_notes'
    ];

    $requiredArrays = [
      'ingredients_used',
      'missing_ingredients',
      'steps',
      'tags'
    ];

    $requiredInts = [
      'cook_time_minutes',
      'difficulty',
      'servings'
    ];

    foreach($requiredStrings as $field) {
      if(!isset($data[$field]) || !is_string($data[$field])) {
        return false;
      }
    }

    foreach($requiredArrays as $field) {
      if(!isset($data[$field]) || !is_array($data[$field])) {
        return false;
      }
    }

    foreach($requiredInts as $field) {
      if(!isset($data[$field]) || !is_int($data[$field])) {
        return false;
      }
    }

    if($data['difficulty'] < 1 || $data['difficulty'] > 10) {
      return false;
    }

    return true;
  }
}