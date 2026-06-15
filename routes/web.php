<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WasteReportController;
use App\Http\Controllers\AdminController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/reports/public', [WasteReportController::class, 'publicIndex'])->name('reports.public');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
});

// Authenticated Citizen Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [WasteReportController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports/create', [WasteReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [WasteReportController::class, 'store'])->name('reports.store');
    
    Route::get('/reports/{report}/edit', [WasteReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [WasteReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [WasteReportController::class, 'destroy'])->name('reports.destroy');
});

// Administrator Routes (Auth + Admin Role)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');
    
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/reports', [AdminController::class, 'reportsIndex'])->name('admin.reports');
    Route::get('/reports/{id}', [AdminController::class, 'showReport'])->name('admin.reports.show');
    Route::patch('/reports/{id}', [AdminController::class, 'updateReport'])->name('admin.reports.update');
});
