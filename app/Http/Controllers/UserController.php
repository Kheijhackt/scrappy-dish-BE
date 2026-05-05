<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

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
}
