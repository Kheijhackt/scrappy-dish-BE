<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipeAiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/recipes/suggest-single', [RecipeAiController::class, 'suggestSingle'])->name('recipe.suggest-single');
Route::post('/recipes/suggest-multiple', [RecipeAiController::class, 'suggestMultiple'])->name('recipe.suggest-multiple');