<?php

namespace App\Http\Controllers;


use App\Http\Requests\SuggestRecipeRequest;
use App\Services\RecipeService;
use App\Http\Resources\RecipeResource;

class RecipeController extends Controller
{
    public function suggest(SuggestRecipeRequest $request, RecipeService $service){
        $result = $service->generateSingle($request->validated());
        try {
            return response()->json(new RecipeResource($result, true, 'Recipe generated successfully'));
        } catch (\Throwable $e) {
            return response()->json(new RecipeResource($result, false, $e->getMessage()), 422);
        }
    }
}