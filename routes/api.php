<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipeAiController;
use App\Http\Controllers\RecipeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/recipes/suggest-single', [RecipeAiController::class, 'suggestSingle'])->name('recipes.suggest-single');
Route::post('/recipes/suggest-multiple', [RecipeAiController::class, 'suggestMultiple'])->name('recipes.suggest-multiple');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/recipes/save', [RecipeController::class, 'saveRecipe'])->name('recipes.save');
    Route::get('/recipes/{id}', [RecipeController::class, 'getRecipeById'])->name('recipes.get-recipe');
    Route::get('/recipes', [RecipeController::class, 'getPaginatedRecipes'])->name('recipes.get-paginated-recipes');
    Route::delete('/recipes/{id}', [RecipeController::class, 'deleteRecipeById'])->name('recipes.delete-recipe');
});