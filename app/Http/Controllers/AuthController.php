<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function continueWithGoogle(AuthRequest $request, AuthService $service)
    {
        $result = null;
        $idToken = $request->input('id_token');
        try {
            $result = $service->authenticateUser($idToken);
            $resource = (new AuthResource($result));

            return ApiResponse::success($resource->toArray($request), 'User signed in successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function verifyUser()
    {
        try {
            return ApiResponse::success([], 'User verified successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function logout(Request $request, AuthService $service)
    {
        $result = null;
        try {
            $result = $service->deleteCurrentToken($request->user());
            $resource = (new UserResource($result));

            return ApiResponse::success($resource->toArray($request), 'User logged out successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }

    public function logoutAll(Request $request, AuthService $service)
    {
        $result = null;
        try {
            $result = $service->deleteAllUserTokens($request->user());
            $resource = (new UserResource($result));

            return ApiResponse::success($resource->toArray($request), 'User logged out of all devices successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }
}
