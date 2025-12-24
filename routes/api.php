<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\ApiDeliveryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DashboardController2;
use App\Http\Controllers\Api\PaymentController;

// Routes publiques (sans authentification)
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Route pour obtenir le profil utilisateur
    Route::get('/user', [AuthController::class, 'user']);
    
    // Route de déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard routes
    Route::get('/dashboard/distributor-stats', [DashboardController2::class, 'distributorDashboard']);
    Route::get('/dashboard/stats', [DashboardController2::class, 'distributorStats']);
    
    // Statistiques détaillées
    Route::get('/dashboard/cards-stats', [DashboardController2::class, 'cardsStats']);
    Route::get('/dashboard/monthly-summary', [DashboardController2::class, 'monthlySummary']);
    Route::get('/dashboard/my-activity', [DashboardController2::class, 'myActivity']);
    Route::get('/dashboard/cards-stock', [DashboardController2::class, 'cardsStock']);
    
    // Admin routes
    Route::get('/dashboard/admin-stats', [DashboardController::class, 'adminStats'])->middleware('role:admin,super_admin');
    Route::get('/dashboard/overview', [DashboardController::class, 'overview'])->middleware('role:admin,super_admin');
    Route::get('/dashboard/wilaya-stats', [DashboardController::class, 'wilayaStats'])->middleware('role:admin,super_admin');
    Route::get('/dashboard/top-distributors', [DashboardController::class, 'topDistributors'])->middleware('role:admin,super_admin');
    Route::get('/dashboard/top-schools', [DashboardController::class, 'topSchools'])->middleware('role:admin,super_admin');
    
    // Wilayas - MODIFIEZ CETTE LIGNE :
    // Route::get('/wilayas', [DashboardController::class, 'getWilayas']); // À SUPPRIMER
    
    // Ajoutez les routes des écoles AVANT les autres
    Route::prefix('schools')->group(function () {
        Route::get('/', [SchoolController::class, 'index']);
        Route::post('/', [SchoolController::class, 'store']);
        Route::post('/distributor-store', [SchoolController::class, 'distributorStore']);
        Route::get('/wilayas', [SchoolController::class, 'getWilayas']); // <-- IMPORTANT
        Route::get('/communes', [SchoolController::class, 'getCommunesByWilaya']); // <-- IMPORTANT
    });
    
    // Routes pour les paiements
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    
    // Routes pour les livraisons
    Route::prefix('deliveries')->group(function () {
        Route::get('/', [ApiDeliveryController::class, 'index']);
        Route::post('/', [ApiDeliveryController::class, 'store']);
        Route::post('/storeWithLocation', [ApiDeliveryController::class, 'storeWithLocation']);
        Route::get('/{id}', [ApiDeliveryController::class, 'show']);
        Route::put('/{id}/status', [ApiDeliveryController::class, 'updateStatus']);
        Route::get('/stats/summary', [ApiDeliveryController::class, 'getStats']);
    });
});