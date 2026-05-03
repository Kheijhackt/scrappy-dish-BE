<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaveRecipeRequest;
use App\Services\RecipeService;
use App\Http\Resources\SaveRecipeResource;
use App\Http\Resources\GetOneRecipeResource;

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

    public function getRecipeById(Request $request, int $id, RecipeService $service)
    {
        $result = null;
        try {
            $result = $service->getRecipeById($request->user(), $id);
            return response()->json(new GetOneRecipeResource($result, true, 'Recipe retrieved successfully'));
        } catch (\Throwable $e) {
            return response()->json(new GetOneRecipeResource($result, false, $e->getMessage()), 422);
        }
    }
}
