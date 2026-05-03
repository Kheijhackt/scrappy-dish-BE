<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveRecipeRequest;
use App\Services\RecipeService;
use App\Http\Resources\SaveRecipeResource;

class RecipeController extends Controller
{
    public function saveRecipe(SaveRecipeRequest $request, RecipeService $service)
    {
        $result = null;
        try {
            $result = $service->saveRecipe($request->user(), $request->validated());
            return response()->json(new SaveRecipeResource($result, true, 'Recipe saved successfully'));
        } catch (\Throwable $e) {
            return response()->json(new SaveRecipeResource($result, false, $e->getMessage()), 422);
        }
    }
}
