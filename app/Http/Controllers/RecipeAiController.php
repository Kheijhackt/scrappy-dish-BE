<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuggestMultipleRecipeRequest;
use App\Http\Requests\SuggestSingleRecipeRequest;
use App\Http\Resources\RecipeMultipleResource;
use App\Services\RecipeAiService;
use App\Http\Resources\RecipeSingleResource;
use App\Traits\ApiResponse;

class RecipeAiController extends Controller
{
    use ApiResponse;
    public function suggestSingle(SuggestSingleRecipeRequest $request, RecipeAiService $service){
        $result = null;
        try {
            $result = $service->generateSingle($request->validated());
            $resource = (new RecipeSingleResource($result))->toArray($request);
            return ApiResponse::success($resource, 'Recipe generated successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function suggestMultiple(SuggestMultipleRecipeRequest $request, RecipeAiService $service){
        $result = null;
        try {
            $result = $service->generateMultiple($request->validated());
            $resource = (new RecipeMultipleResource($result))->toArray($request);
            return ApiResponse::success($resource, 'Recipes generated successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }
}