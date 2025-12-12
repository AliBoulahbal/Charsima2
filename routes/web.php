<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\KioskController; // <-- AJOUTÉ
use App\Http\Middleware\Role;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================== ROUTES ADMIN ====================
Route::middleware(['auth', Role::class . ':admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users Management

    Route::resource('users', UserController::class);
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])
    ->name('users.assign-role');

    // Schools Management
    Route::get('schools/export', [SchoolController::class, 'export'])->name('schools.export');
    Route::resource('schools', SchoolController::class);

    // Distributors Management
    Route::get('distributors/report/{distributor}', [DistributorController::class, 'financialReport'])->name('distributors.financial-report');
    Route::resource('distributors', DistributorController::class);

    // Kiosks Management
    Route::get('kiosks/{kiosk}/sales', [KioskController::class, 'sales'])->name('kiosks.sales');
    Route::get('kiosks/{kiosk}/report', [KioskController::class, 'financialReport'])->name('kiosks.financial-report');
    Route::resource('kiosks', KioskController::class);

    // Deliveries Management
    Route::get('deliveries/export', [DeliveryController::class, 'export'])->name('deliveries.export');
    Route::get('deliveries/statistics', [DeliveryController::class, 'statistics'])->name('deliveries.statistics');
    Route::resource('deliveries', DeliveryController::class);

    // Payments Management
    Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('payments/report', [PaymentController::class, 'financialReport'])->name('payments.financial-report');
    Route::resource('payments', PaymentController::class);
});

// ==================== REDIRECTION PAR DÉFAUT ====================
Route::get('/', function () {
    return redirect()->route('login');
});