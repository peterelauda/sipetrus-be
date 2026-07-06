<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Expense\ExpenseController;
use App\Http\Controllers\Product\ProductBatchController;
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
    Route::post('/', [ProductController::class, 'createProduct'])->middleware('role:admin');
    Route::get('/', [ProductController::class, 'getProducts']);
    Route::get('/search', [ProductController::class, 'searchProduct']);
    Route::get('/low-stock', [ProductController::class, 'getLowStockProducts']);
    Route::get('/{id}', [ProductController::class, 'getProductById']);
    Route::put('/{id}', [ProductController::class, 'updateProductById'])->middleware('role:admin');
    Route::post('/{id}/adjust-stock', [ProductController::class, 'adjustStock'])->middleware('role:admin');
    Route::delete('/{id}', [ProductController::class, 'deleteProductById'])->middleware('role:admin');
    Route::post('/{id}/batches', [ProductBatchController::class, 'createBatch'])->middleware('role:admin');
    Route::get('/{id}/batches', [ProductBatchController::class, 'getProductBatches']);
});

Route::prefix('/stocks')->middleware('auth:sanctum')->group(function () {
    Route::get('/expired-stock', [ProductBatchController::class, 'getExpiredStocks']);
    Route::get('/near-expired', [ProductBatchController::class, 'getNearExpiredStocks']);
});

Route::prefix('/stock-movements')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [StockMovementController::class, 'getStockMovements'])->middleware('role:admin');
});

Route::prefix('/transactions')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [TransactionController::class, 'getTransactions']);
    Route::get('/{id}', [TransactionController::class, 'getTransactionById']);
    Route::post('/', [TransactionController::class, 'storeTransaction']);
    Route::put('{id}/cancel', [TransactionController::class, 'cancelTransactionById'])->middleware('role:admin');
});

Route::prefix('/expenses')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [ExpenseController::class, 'createExpense'])->middleware('role:admin');
    Route::get('/', [ExpenseController::class, 'getExpenses']);
    Route::get('/{id}', [ExpenseController::class, 'getExpenseById']);
    Route::put('/{id}', [ExpenseController::class, 'updateExpense'])->middleware('role:admin');
    Route::delete('/{id}', [ExpenseController::class, 'deleteExpense'])->middleware('role:admin');
});

Route::prefix('/dashboard')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [DashboardController::class, 'getDashboard']);
});

Route::prefix('/reports')->middleware('auth:sanctum')->group(function () {
    Route::get('/sales', [ReportController::class, 'getSalesReport'])->middleware('role:admin');
});
