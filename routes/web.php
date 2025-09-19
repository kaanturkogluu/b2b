<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Redirect root to login
Route::middleware('web')->get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected Routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // User Management Routes (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::post('users/{user}/toggle-status', [\App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Service Management Routes
        Route::resource('bakim', \App\Http\Controllers\BakimController::class);
        Route::get('/bakim/{bakim}/print', [\App\Http\Controllers\BakimController::class, 'print'])->name('bakim.print');
        Route::get('/bakim/export/excel', [\App\Http\Controllers\BakimController::class, 'exportExcel'])->name('bakim.export.excel');
        Route::post('/bakim/{bakim}/approve-payment', [\App\Http\Controllers\BakimController::class, 'approvePayment'])->name('bakim.approve-payment');
        
        // Invoice Settings Routes
        Route::get('/invoice-settings', [\App\Http\Controllers\InvoiceSettingsController::class, 'index'])->name('invoice-settings.index');
        Route::put('/invoice-settings', [\App\Http\Controllers\InvoiceSettingsController::class, 'update'])->name('invoice-settings.update');
        
        // Reports Routes
        Route::get('/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/service-report', [\App\Http\Controllers\ReportsController::class, 'serviceReport'])->name('reports.service-report');
        // Mali rapor ve personel raporu route'ları kaldırıldı - sistem yükseltmesi gerekli
        
    });
    
    // Staff Routes
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/staff/dashboard', [\App\Http\Controllers\StaffController::class, 'dashboard'])->name('staff.dashboard');
        Route::get('/staff/bakim', [\App\Http\Controllers\BakimController::class, 'staffIndex'])->name('staff.bakim.index');
        Route::get('/staff/bakim/{bakim}', [\App\Http\Controllers\BakimController::class, 'staffShow'])->name('staff.bakim.show');
        Route::post('/staff/bakim/{bakim}/complete', [\App\Http\Controllers\StaffController::class, 'completeMaintenance'])->name('staff.bakim.complete');
        Route::get('/staff/profile', [\App\Http\Controllers\StaffController::class, 'profile'])->name('staff.profile');
        Route::get('/staff/tasks', [\App\Http\Controllers\StaffController::class, 'tasks'])->name('staff.tasks');
        Route::get('/staff/timeline', [\App\Http\Controllers\StaffController::class, 'timeline'])->name('staff.timeline');
    });
});
