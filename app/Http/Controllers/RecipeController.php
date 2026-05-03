<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SaveRecipeRequest;
use App\Services\RecipeService;
use App\Http\Resources\SaveRecipeResource;
use App\Http\Resources\GetOneRecipeResource;

class RecipeController extends Controller
{
    use ApiResponse;
    public function saveRecipe(SaveRecipeRequest $request, RecipeService $service)
    {
        $result = null;
        try {
            $result = $service->saveRecipe($request->user(), $request->validated());
            $resource = (new SaveRecipeResource($result))->toArray($request);
            return Apiresponse::success($resource, 'Recipe saved successfully');
        } catch (\Throwable $e) {
            return Apiresponse::error([], $e->getMessage());
        }
    }

    public function getRecipeById(Request $request, int $id, RecipeService $service)
    {
        $result = null;
        try {
            $result = $service->getRecipeById($request->user(), $id);
            $resource = (new GetOneRecipeResource($result))->toArray($request);
            return Apiresponse::success($resource, 'Recipe retrieved successfully');
        } catch (\Throwable $e) {
            return Apiresponse::error([], $e->getMessage());
        }
    }
}
