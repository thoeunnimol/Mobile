<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceSettingsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HeroSectionController;
use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/login', [AdminAuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AdminAuthController::class, 'user']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::put('/profile', [AdminAuthController::class, 'updateProfile']);
    });
});

// Customer routes
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::post('/', [CustomerController::class, 'store']);
    Route::put('/{customer}', [CustomerController::class, 'update']);
    Route::delete('/{customer}', [CustomerController::class, 'destroy']);
    Route::post('/{customer}/toggle-active', [CustomerController::class, 'toggleActive']);
});

// Product routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{product}', [ProductController::class, 'update']);
    Route::delete('/{product}', [ProductController::class, 'destroy']);
    Route::post('/{product}/toggle-active', [ProductController::class, 'toggleActive']);
});

// Order Routes
Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('/{order}', [OrderController::class, 'show']);
    Route::put('/{order}/status', [OrderController::class, 'updateStatus']);
    Route::put('/{order}/payment-status', [OrderController::class, 'updatePaymentStatus']);
    Route::delete('/{order}', [OrderController::class, 'destroy']);
});

// Invoice Routes
Route::prefix('invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'index']);
    Route::post('/', [InvoiceController::class, 'store']);
    Route::get('/{invoice}', [InvoiceController::class, 'show']);
    Route::put('/{invoice}', [InvoiceController::class, 'update']);
    Route::put('/{invoice}/status', [InvoiceController::class, 'updateStatus']);
    Route::delete('/{invoice}', [InvoiceController::class, 'destroy']);
    Route::get('/{invoice}/pdf', [InvoiceController::class, 'generatePdf']);
    Route::post('/{invoice}/send', [InvoiceController::class, 'sendInvoice']);
    Route::get('/{invoice}/download', [InvoiceController::class, 'downloadPdf']);
});

Route::prefix('invoice-settings')->group(function () {
    Route::get('/', [InvoiceSettingsController::class, 'index']);
    Route::post('/', [InvoiceSettingsController::class, 'store']);
});

// Dashboard Routes
Route::prefix('dashboard')->group(function () {
    Route::get('/stats', [App\Http\Controllers\DashboardController::class, 'getStats']);
    Route::get('/recent-orders', [App\Http\Controllers\DashboardController::class, 'getRecentOrders']);
    Route::get('/top-products', [App\Http\Controllers\DashboardController::class, 'getTopProducts']);
    Route::get('/revenue-chart', [App\Http\Controllers\DashboardController::class, 'getRevenueChart']);
    Route::get('/sales-distribution', [App\Http\Controllers\DashboardController::class, 'getSalesDistribution']);
});

Route::apiResource('categories', CategoryController::class);

// Hero Section Routes
Route::get('/hero-sections', [HeroSectionController::class, 'index']);
Route::get('/hero-sections/{page}', [HeroSectionController::class, 'show']);
Route::post('/hero-sections', [HeroSectionController::class, 'store']);
Route::put('/hero-sections/{id}', [HeroSectionController::class, 'update']);
Route::delete('/hero-sections/{id}', [HeroSectionController::class, 'destroy']);

// Customer Authentication Routes
Route::prefix('customer')->group(function () {
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::post('login', [CustomerAuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [CustomerAuthController::class, 'logout']);
        Route::get('profile', [CustomerAuthController::class, 'profile']);
        Route::put('profile', [CustomerAuthController::class, 'updateProfile']);
        Route::get('orders', [OrderController::class, 'customerOrders']);
    });
});

