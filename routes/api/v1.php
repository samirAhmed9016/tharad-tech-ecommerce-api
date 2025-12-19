<?php

use App\Http\Controllers\API\Auth\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/test', fn() => response()->json(['version' => 'v1']));






Route::post('auth/register', [AuthController::class, 'register']);
Route::middleware('throttle:5,1')->post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
