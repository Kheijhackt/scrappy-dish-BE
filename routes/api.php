<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipeAiController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::post('/recipes/suggest-single', [RecipeAiController::class, 'suggestSingle'])->name('recipes.suggest-single');
Route::post('/recipes/suggest-multiple', [RecipeAiController::class, 'suggestMultiple'])->name('recipes.suggest-multiple');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getCurrentUser'])->name('users.get-current-user');
    Route::patch('/user', [UserController::class, 'updateCurrentUser'])->name('users.update-current-user');
    Route::delete('/user', [UserController::class, 'deleteCurrentUser'])->name('users.delete-current-user');

    Route::post('/recipes/save', [RecipeController::class, 'saveRecipe'])->name('recipes.save');
    Route::get('/recipes/{id}', [RecipeController::class, 'getRecipeById'])->name('recipes.get-recipe');
    Route::get('/recipes', [RecipeController::class, 'getPaginatedRecipes'])->name('recipes.get-paginated-recipes');
    Route::delete('/recipes/{id}', [RecipeController::class, 'deleteRecipeById'])->name('recipes.delete-recipe');

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});