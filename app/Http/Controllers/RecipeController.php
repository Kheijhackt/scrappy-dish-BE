<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SuggestRecipeRequest;
use App\Services\RecipeService;

class RecipeController extends Controller
{
    public function suggest(SuggestRecipeRequest $request, RecipeService $service){
        $result = $service->generate($request->validated());

        return response()->json($result);
    }
}
