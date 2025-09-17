<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD

Route::get('/', function () {
    return view('welcome');
=======
use App\Http\Controllers\AuthController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // User Management Routes (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::post('users/{user}/toggle-status', [\App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Service Management Routes
        Route::resource('bakim', \App\Http\Controllers\BakimController::class);
        Route::get('/bakim/{bakim}/print', [\App\Http\Controllers\BakimController::class, 'print'])->name('bakim.print');
        
        // Invoice Settings Routes
        Route::get('/invoice-settings', [\App\Http\Controllers\InvoiceSettingsController::class, 'index'])->name('invoice-settings.index');
        Route::put('/invoice-settings', [\App\Http\Controllers\InvoiceSettingsController::class, 'update'])->name('invoice-settings.update');
        
        // Reports Routes
        Route::get('/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/service-report', [\App\Http\Controllers\ReportsController::class, 'serviceReport'])->name('reports.service-report');
        Route::get('/reports/financial-report', [\App\Http\Controllers\ReportsController::class, 'financialReport'])->name('reports.financial-report');
        Route::get('/reports/staff-report', [\App\Http\Controllers\ReportsController::class, 'staffReport'])->name('reports.staff-report');
        
    });
    
    // Staff Routes
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/staff/bakim', [\App\Http\Controllers\BakimController::class, 'staffIndex'])->name('staff.bakim.index');
    });
>>>>>>> 7f6167b (Temel)
});
