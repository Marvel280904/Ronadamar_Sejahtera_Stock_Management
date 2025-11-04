<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Products Management
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/manage-stock', [ProductController::class, 'manageStock'])->name('products.manage-stock');

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Reports
    Route::get('/reports/stock/pdf', [ReportController::class, 'stockPdf'])->name('reports.stock.pdf');

    // API Tester Page
    Route::get('/api-tester', function () {
        return view('api-tester');
    })->name('api.tester');
});