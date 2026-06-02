<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Stock\StockMovementController;
use App\Http\Controllers\Transaction\TransactionController;
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
    Route::get('/{id}', [ProductController::class, 'getProductById']);
    Route::put('/{id}', [ProductController::class, 'updateProductById']);
    Route::post('/{id}/adjust-stock', [ProductController::class, 'adjustStock']);
    Route::delete('/{id}', [ProductController::class, 'deleteProductById']);
});

Route::prefix('/stock-movements')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [StockMovementController::class, 'getStockMovements']);
});

Route::prefix('/transactions')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [TransactionController::class, 'getTransactions']);
    Route::get('/{id}', [TransactionController::class, 'getTransactionById']);
    Route::post('/', [TransactionController::class, 'storeTransaction']);
    Route::put('{id}/cancel', [TransactionController::class, 'cancelTransactionById']);
});

Route::prefix('/dashboard')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [DashboardController::class, 'getDashboard']);
});

Route::prefix('/reports')->middleware('auth:sanctum')->group(function () {
    Route::get('/sales', [ReportController::class, 'getSalesReport']);
});