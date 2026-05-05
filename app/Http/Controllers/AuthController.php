<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    use ApiResponse;
    
    public function logout(Request $request, AuthService $service) {
        $result = null;
        try {
            $result = $service->deleteCurrentToken($request->user());
            $resource = (new UserResource($result));
            return ApiResponse::success($resource->toArray($request), 'User logged out successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error([], $e->getMessage());
        }
    }
}
