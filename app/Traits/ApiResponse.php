<?php

namespace App\Traits;

trait ApiResponse {
    function apiResponse($statusCode, $message, $data = []) {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
