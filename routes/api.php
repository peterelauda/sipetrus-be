<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'Hello!']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('/products')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [ProductController::class, 'createProduct']);
    Route::get('/', [ProductController::class, 'getProducts']);
    Route::get('/search', [ProductController::class, 'searchProduct']);
});