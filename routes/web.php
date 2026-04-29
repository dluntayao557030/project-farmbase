<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// ── Guest Routes ─────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Authenticated Routes ───────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Barn Owner Routes
    Route::middleware('can:barn_owner')->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
             ->name('dashboard');

        Route::resource('inventory', \App\Http\Controllers\BarnSupplyController::class)
              ->only(['index', 'store', 'update','destroy','show']);

        Route::patch('/inventory/{inventory}/toggle', [BarnSupplyController::class, 'toggle'])
        ->name('inventory.toggle');
              
        Route::resource('suppliers', \App\Http\Controllers\BarnSupplierController::class)
              ->only(['index', 'store', 'update', 'destroy']);

        Route::resource('staffs', \App\Http\Controllers\BarnStaffController::class)
             ->only(['index', 'store', 'update', 'destroy']);

        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])
             ->name('reports.index');

        Route::post('/reports/generate', [\App\Http\Controllers\ReportController::class, 'generate'])
             ->name('reports.generate');

        Route::get('/dashboard/kpi/inventory',  [\App\Http\Controllers\DashboardController::class, 'kpiInventory'])->name('dashboard.kpi.inventory');
        
        Route::get('/dashboard/kpi/stock-in',   [\App\Http\Controllers\DashboardController::class, 'kpiStockIn'])->name('dashboard.kpi.stockIn');

        Route::get('/dashboard/kpi/stock-out',  [\App\Http\Controllers\DashboardController::class, 'kpiStockOut'])->name('dashboard.kpi.stockOut');

    });

    // Barn Staff Routes
    Route::middleware('can:barn_staff')->group(function () {

        Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'index'])
             ->name('transactions.index');

        Route::post('/transactions/stock-in', [\App\Http\Controllers\TransactionController::class, 'stockIn'])
             ->name('transactions.stockIn');

        Route::post('/transactions/stock-out', [\App\Http\Controllers\TransactionController::class, 'stockOut'])
             ->name('transactions.stockOut');

        Route::get('/transactions/history-data', [\App\Http\Controllers\TransactionController::class, 'historyData'])
             ->name('transactions.historyData');

    });

});