<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function getCurrentUser(Request $request)
    {
        $result = $request->user();
        try {
            $resource = (new UserResource($result));

            return ApiResponse::success($resource->toArray($request), 'User retrieved successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function updateCurrentUser(UpdateUserRequest $request, UserService $service)
    {
        $result = null;
        try {
            $result = $service->updateCurrentUser($request->user(), $request->validated());
            $resource = (new UserResource($result));

            return ApiResponse::success($resource->toArray($request), 'User updated successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function deleteCurrentUser(Request $request, UserService $service)
    {
        $result = null;
        try {
            $result = $service->deleteCurrentUser($request->user());
            $resource = (new UserResource($result));

            return ApiResponse::success($resource->toArray($request), 'User deleted successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }
}
