<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuggestMultipleRecipeRequest;
use App\Http\Resources\RecipeMultipleResource;
use App\Http\Resources\RecipeSingleResource;
use App\Services\RecipeAiService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RecipeAiController extends Controller
{
    use ApiResponse;

    public function suggestSingle(Request $request, RecipeAiService $service)
    {
        $result = null;

        $result = $service->generateSingle($request->user(), []);
        $resource = (new RecipeSingleResource($result));

        return ApiResponse::success($resource->toArray($request), 'Recipe generated successfully');

    }

    public function suggestMultiple(SuggestMultipleRecipeRequest $request, RecipeAiService $service)
    {
        $result = null;
        try {
            $result = $service->generateMultiple($request->user(), $request->validated());
            $resource = (new RecipeMultipleResource($result));

            return ApiResponse::success($resource->toArray($request), 'Recipes generated successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }
}
