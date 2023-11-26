<?php

use App\Http\Controllers\Api\V1\ForgotPasswordController;
use App\Http\Controllers\Api\V1\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1'
], function() {

    Route::post('auth/login', LoginController::class);
    Route::post('auth/forgot-password', ForgotPasswordController::class);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
