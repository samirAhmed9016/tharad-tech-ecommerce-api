<?php

use App\Http\Controllers\API\Auth\V1\AuthController;
use App\Http\Controllers\Api\Carts\V1\CartController;
use App\Http\Controllers\Api\Orders\V1\OrderController;
use App\Http\Controllers\Api\Products\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/test', fn() => response()->json(['version' => 'v1']));






Route::post('auth/register', [AuthController::class, 'register']);
Route::middleware('throttle:5,1')->post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);


    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart', [CartController::class, 'store']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);
    Route::delete('cart/clear', [CartController::class, 'clear']);


    Route::post('checkout', [OrderController::class, 'checkout']);
});
