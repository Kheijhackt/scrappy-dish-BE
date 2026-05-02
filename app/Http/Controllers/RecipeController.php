<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuggestMultipleRecipeRequest;
use App\Http\Requests\SuggestSingleRecipeRequest;
use App\Http\Resources\RecipeMultipleResource;
use App\Services\RecipeService;
use App\Http\Resources\RecipeSingleResource;

class RecipeController extends Controller
{
    public function suggestSingle(SuggestSingleRecipeRequest $request, RecipeService $service){
        $result = null;
        try {
            $result = $service->generateSingle($request->validated());
            return response()->json(new RecipeSingleResource($result, true, 'Recipe generated successfully'));
        } catch (\Throwable $e) {
            return response()->json(new RecipeSingleResource($result, false, $e->getMessage()), 422);
        }
    }

    public function suggestMultiple(SuggestMultipleRecipeRequest $request, RecipeService $service){
        $result = null;
        try {
            $result = $service->generateMultiple($request->validated());
            return response()->json(new RecipeMultipleResource($result, true, 'Recipes generated successfully'));
        } catch (\Throwable $e) {
            return response()->json(new RecipeMultipleResource($result, false, $e->getMessage()), 422);
        }
    }
}