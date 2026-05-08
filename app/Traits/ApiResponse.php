<?php

namespace App\Traits;

trait ApiResponse
{
    public static function success(array $data, string $message)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public static function error(array $data, string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], 422);
    }
}
