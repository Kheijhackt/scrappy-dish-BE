<?php

namespace App\Http\Controllers;


use App\Http\Requests\SuggestSingleRecipeRequest;
use App\Services\RecipeService;
use App\Http\Resources\RecipeSingleResource;

class RecipeController extends Controller
{
    public function suggestSingle(SuggestSingleRecipeRequest $request, RecipeService $service){
        $result = $service->generateSingle($request->validated());
        try {
            return response()->json(new RecipeSingleResource($result, true, 'Recipe generated successfully'));
        } catch (\Throwable $e) {
            return response()->json(new RecipeSingleResource($result, false, $e->getMessage()), 422);
        }
    }
}