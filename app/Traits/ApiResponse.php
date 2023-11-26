<?php

namespace App\Traits;

trait ApiResponse {
    function apiResponse($statusCode = 200, $message, $data = []) {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
