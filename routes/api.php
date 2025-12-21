<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ProductImageController;

// Public routes (no auth required)
Route::get('/user', function (Request $request) {
    return $request->user();
});

// Public routes for landing page
Route::get('/products/public', [ProductController::class, 'publicIndex']);

// ========== PUBLIC AUTH ROUTES (NO MIDDLEWARE) ==========
Route::prefix('admin')->group(function () {
    // These routes must be OUTSIDE auth middleware
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/check-auth', [AuthController::class, 'checkAuth']);
    
    // Also add CSRF token route for web apps
 
});

// ========== PROTECTED ADMIN ROUTES ==========
Route::prefix('admin')->middleware(['auth.admin'])->group(function () {
    // Auth routes (except login/check-auth)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Dashboard routes
    Route::get('/dashboard/metrics', [DashboardController::class, 'getMetrics']);
    Route::get('/dashboard/sales-trend', [DashboardController::class, 'getSalesTrend']);
    Route::get('/dashboard/expenses', [DashboardController::class, 'getExpenses']);

    // Product routes
    Route::apiResource('products', ProductController::class);
    Route::patch('products/{id}/stock', [ProductController::class, 'updateStock']);
    Route::get('/products/search', [ProductController::class, 'search']); // Fixed path

    // Sales routes
    Route::apiResource('sales', SalesController::class);
    Route::get('sales/stats', [SalesController::class, 'stats']);
    Route::get('sales/top-products', [SalesController::class, 'topProducts']);
    Route::get('sales/trend', [SalesController::class, 'trend']);
    Route::post('sales/{id}/process', [SalesController::class, 'processOrder']);
    Route::post('sales/{id}/cancel', [SalesController::class, 'cancelOrder']);

    // Employees routes
    Route::apiResource('employees', EmployeesController::class);

    // Inventory routes
    Route::get('inventory/stats', [InventoryController::class, 'stats']);

    // Product Image Routes
    Route::prefix('products/{product}/images')->group(function () {
        Route::get('/', [ProductImageController::class, 'index']);
        Route::post('/', [ProductImageController::class, 'store']);
        Route::put('/{image}/set-primary', [ProductImageController::class, 'setPrimary']);
        Route::put('/reorder', [ProductImageController::class, 'reorder']);
        Route::put('/{image}/update', [ProductImageController::class, 'update']);
        Route::delete('/multiple', [ProductImageController::class, 'destroyMultiple']);
        Route::delete('/{image}', [ProductImageController::class, 'destroy']);
    });
});

