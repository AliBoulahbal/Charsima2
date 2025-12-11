<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MobileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ==================== ROUTES PUBLIQUES ====================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // Optionnel

// ==================== ROUTES PROTÉGÉES ====================
Route::middleware(['auth:sanctum'])->group(function () {
    
    // -------------------- AUTHENTIFICATION --------------------
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // -------------------- UTILISATEURS --------------------
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole']);
    });
    
    // -------------------- ÉCOLES --------------------
    Route::get('/schools', [SchoolController::class, 'index']);
    Route::get('/schools/{school}', [SchoolController::class, 'show']);
    Route::post('/schools/nearby', [SchoolController::class, 'nearby']);
    Route::post('/schools/{school}/check-location', [SchoolController::class, 'checkLocation']);
    
    // Routes réservées aux admins
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::post('/schools', [SchoolController::class, 'store']);
        Route::put('/schools/{school}', [SchoolController::class, 'update']);
        Route::delete('/schools/{school}', [SchoolController::class, 'destroy']);
        Route::get('/schools/{school}/deliveries', [SchoolController::class, 'schoolDeliveries']);
    });
    
    // -------------------- LIVRAISONS --------------------
    Route::get('/deliveries', [DeliveryController::class, 'index']); // Toutes les livraisons (filtrées par rôle)
    Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show']);
    Route::post('/deliveries', [DeliveryController::class, 'store']);
    Route::post('/deliveries/with-location', [DeliveryController::class, 'storeWithLocation']);
    
    // Routes spécifiques distributeur
    Route::middleware(['role:distributor'])->group(function () {
        Route::get('/my-deliveries', [DeliveryController::class, 'myDeliveries']);
        Route::get('/my-deliveries/stats', [DeliveryController::class, 'myDeliveriesStats']);
        Route::get('/deliveries/school/{school}', [DeliveryController::class, 'deliveriesBySchool']);
    });
    
    // Routes réservées aux admins
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/all-deliveries', [DeliveryController::class, 'allDeliveries']);
        Route::put('/deliveries/{delivery}', [DeliveryController::class, 'update']);
        Route::delete('/deliveries/{delivery}', [DeliveryController::class, 'destroy']);
        Route::get('/deliveries/statistics', [DeliveryController::class, 'statistics']);
        Route::get('/deliveries/export', [DeliveryController::class, 'export']);
    });
    
    // -------------------- PAIEMENTS --------------------
    Route::get('/payments', [PaymentController::class, 'index']); // Tous les paiements (filtrés par rôle)
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
    Route::post('/payments', [PaymentController::class, 'store']);
    
    // Routes spécifiques distributeur
    Route::middleware(['role:distributor'])->group(function () {
        Route::get('/my-payments', [PaymentController::class, 'myPayments']);
        Route::get('/my-payments/stats', [PaymentController::class, 'myPaymentsStats']);
        Route::post('/payments/for-delivery/{delivery}', [PaymentController::class, 'storeForDelivery']);
    });
    
    // Routes réservées aux admins
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/all-payments', [PaymentController::class, 'allPayments']);
        Route::put('/payments/{payment}', [PaymentController::class, 'update']);
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);
        Route::get('/payments/financial-report', [PaymentController::class, 'financialReport']);
        Route::get('/payments/export', [PaymentController::class, 'export']);
    });
    
    // -------------------- DASHBOARD & STATISTIQUES --------------------
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Dashboard distributeur
    Route::middleware(['role:distributor'])->group(function () {
        Route::get('/dashboard/distributor-stats', [DashboardController::class, 'distributorStats']);
        Route::get('/dashboard/my-activity', [DashboardController::class, 'myActivity']);
        Route::get('/dashboard/monthly-summary', [DashboardController::class, 'monthlySummary']);
    });
    
    // Dashboard admin
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/dashboard/admin-stats', [DashboardController::class, 'adminStats']);
        Route::get('/dashboard/overview', [DashboardController::class, 'overview']);
        Route::get('/dashboard/wilaya-stats', [DashboardController::class, 'wilayaStats']);
        Route::get('/dashboard/top-distributors', [DashboardController::class, 'topDistributors']);
        Route::get('/dashboard/top-schools', [DashboardController::class, 'topSchools']);
    });
    
    // -------------------- GÉOLOCALISATION --------------------
    Route::post('/location/validate', [MobileController::class, 'validateLocation']);
    Route::post('/location/schools/nearby', [MobileController::class, 'getNearbySchools']);
    Route::post('/location/record', [MobileController::class, 'recordLocation']);
    
    // -------------------- RAPPORTS --------------------
    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/reports/deliveries', [DeliveryController::class, 'generateReport']);
        Route::get('/reports/payments', [PaymentController::class, 'generateReport']);
        Route::get('/reports/schools', [SchoolController::class, 'generateReport']);
        Route::get('/reports/distributors', [UserController::class, 'generateReport']);
    });
    
    // -------------------- DISTRIBUTEURS --------------------
    Route::middleware(['role:distributor'])->group(function () {
        Route::get('/distributor/profile', [UserController::class, 'distributorProfile']);
        Route::put('/distributor/profile', [UserController::class, 'updateDistributorProfile']);
        Route::get('/distributor/schools', [SchoolController::class, 'distributorSchools']);
    });
    
    // -------------------- UTILES --------------------
    Route::get('/wilayas', [SchoolController::class, 'getWilayas']);
    Route::get('/payment-methods', [PaymentController::class, 'getPaymentMethods']);
    Route::get('/school-types', [SchoolController::class, 'getSchoolTypes']);
});

// ==================== ROUTES POUR TEST ====================
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
    Route::get('/test/schools', function () {
        return response()->json([
            'message' => 'Test réussi',
            'timestamp' => now(),
            'schools_count' => \App\Models\School::count(),
        ]);
    });
    
    Route::get('/test/auth', function () {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->user(),
            'roles' => auth()->user()->getRoleNames(),
        ]);
    });
});

// ==================== ROUTES PING/SANTÉ ====================
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'service' => 'Charisma Management API',
        'version' => '1.0.0',
    ]);
});

Route::get('/ping', function () {
    return response()->json(['pong' => now()->toDateTimeString()]);
});

// ==================== FALLBACK ====================
Route::fallback(function () {
    return response()->json([
        'error' => 'Route non trouvée',
        'message' => 'La route demandée n\'existe pas',
        'available_routes' => [
            'GET /api/health',
            'POST /api/login',
            'GET /api/schools',
            'POST /api/deliveries',
            // ... autres routes principales
        ]
    ], 404);
});