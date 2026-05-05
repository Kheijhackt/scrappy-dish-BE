<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SaveRecipeRequest;
use App\Services\RecipeService;
use App\Http\Resources\SaveRecipeResource;
use App\Http\Resources\GetOneRecipeResource;
use App\Http\Resources\GetRecipesSummaryResource;
use App\Http\Resources\DeleteOneRecipeResource;
use App\Traits\ApiResponse;

class RecipeController extends Controller
{
    use ApiResponse;
    public function saveRecipe(SaveRecipeRequest $request, RecipeService $service)
    {
        $result = null;
        try {
            $result = $service->saveRecipe($request->user(), $request->validated());
            $resource = (new SaveRecipeResource($result));
            return ApiResponse::success($resource->toArray($request), 'Recipe saved successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function getRecipeById(Request $request, int $id, RecipeService $service)
    {
        $result = null;
        try {
            $result = $service->getRecipeById($request->user(), $id);
            $resource = (new GetOneRecipeResource($result));
            return ApiResponse::success($resource->toArray($request), 'Recipe retrieved successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function getPaginatedRecipes(Request $request, RecipeService $service)
    {
        $perPage = min($request->input('perPage', 15), 50);
        $user = $request->user();

        $result = null;
        try {
            $result = $service->getPaginatedRecipes($user, $perPage);
            $resource = GetRecipesSummaryResource::collection($result);
            $resource = [
                'recipes' => $resource,
                'pagination' => [
                    'current_page' => $result->currentPage(),
                    'last_page' => $result->lastPage(),
                    'per_page' => $perPage,
                    'total' => $result->total()
                ]
            ];

            if ($result ->isEmpty()) {
                return ApiResponse::success($resource, 'No recipes found');
            }

            return ApiResponse::success($resource, 'Summary of Recipes retrieved successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function deleteRecipeById(Request $request, int $id, RecipeService $service)
    {
        $result = null;
        try {
            $result = $service->deleteRecipeById($request->user(), $id);
            $resource = (new DeleteOneRecipeResource($result));
            return ApiResponse::success($resource->toArray($request), 'Recipe deleted successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }
}
