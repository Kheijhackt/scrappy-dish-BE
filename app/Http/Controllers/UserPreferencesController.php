<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\UserPreferencesResource;
use App\Services\UserPreferencesService;
use App\Http\Requests\UserPreferencesRequest;

class UserPreferencesController extends Controller
{
    use ApiResponse;
    public function getUserPreferences(Request $request, UserPreferencesService $service) {
        $user = $request->user();
        
        try{
            $result = $service->getUserPreference($user);
            $resource = (new UserPreferencesResource($result));

            return ApiResponse::success($resource->toArray($request), 'User preferences retrieved successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }   
    }

    public function updateUserPreferences(UserPreferencesRequest $request, UserPreferencesService $service) {
        $user = $request->user();
        
        try{
            $result = $service->updateUserPreference($user, $request->validated());
            $resource = (new UserPreferencesResource($result));

            return ApiResponse::success($resource->toArray($request), 'User preferences updated successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }   
    }
}
