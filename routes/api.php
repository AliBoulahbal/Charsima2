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
    Route::get('/schools/wilayas', [SchoolController::class, 'getWilayas']);
    
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
    Route::post('/deliveries/storeWithLocation', [DeliveryController::class, 'storeWithLocation']);
    
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
    
    // -------------------- UTILES --------------------
    Route::get('/wilayas', [DashboardController::class, 'getWilayas']);
    Route::get('/payment-methods', [PaymentController::class, 'getPaymentMethods']);
    Route::get('/school-types', [SchoolController::class, 'getSchoolTypes']);
});

// ==================== ROUTES POUR DISTRIBUTEURS ====================
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Dashboard Distributeur
    Route::get('/distributor/dashboard', [DashboardController::class, 'distributorDashboard']);
    Route::get('/dashboard/distributor-stats', [DashboardController::class, 'distributorStats']);
    Route::get('/dashboard/my-activity', [DashboardController::class, 'myActivity']);
    Route::get('/dashboard/monthly-summary', [DashboardController::class, 'monthlySummary']);
    
    // Profile et écoles du distributeur
    Route::get('/distributor/profile', [UserController::class, 'distributorProfile']);
    Route::put('/distributor/profile', [UserController::class, 'updateDistributorProfile']);
    
    // Routes pour les écoles (distributeur)
    Route::get('/distributor/schools', [SchoolController::class, 'distributorSchools']);
    Route::post('/distributor/schools', [SchoolController::class, 'distributorStore']);
    
    // API pour les listes
    Route::get('/schools/list', [SchoolController::class, 'listForDistributor']);
});

// ==================== ROUTES POUR ADMIN ====================
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
    Route::get('/dashboard/admin-stats', [DashboardController::class, 'adminStats']);
    Route::get('/dashboard/overview', [DashboardController::class, 'overview']);
    Route::get('/dashboard/wilaya-stats', [DashboardController::class, 'wilayaStats']);
    Route::get('/dashboard/top-distributors', [DashboardController::class, 'topDistributors']);
    Route::get('/dashboard/top-schools', [DashboardController::class, 'topSchools']);
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
            'roles' => auth()->user()->role,
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

// ==================== ROUTES DE DIAGNOSTIC ====================
Route::middleware(['auth:sanctum'])->group(function () {
    // Test simple d'écoles
    Route::get('/debug/schools', function() {
        try {
            $schools = \App\Models\School::all();
            return response()->json([
                'success' => true,
                'count' => $schools->count(),
                'schools' => $schools,
                'user' => auth()->user()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });
    
    // Test de la route distributor/schools
    Route::get('/debug/distributor-schools', function() {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'user_role' => $user->role,
            'distributor_id' => $user->distributor_id ?? null,
            'message' => 'Route debug pour distributor/schools'
        ]);
    });
    
    // Test de toutes les écoles sans filtre
    Route::get('/debug/all-schools', function() {
        $schools = \App\Models\School::select('id', 'name', 'commune', 'wilaya')->get();
        return response()->json([
            'success' => true,
            'count' => $schools->count(),
            'schools' => $schools
        ]);
    });
    
    // Route de test pour la création d'école
    Route::get('/debug/test-school-creation', function() {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'role' => $user->role,
            'can_create_school' => $user->role === 'distributor',
            'available_routes' => [
                'POST /api/distributor/schools',
                'POST /api/schools (admin only)'
            ]
        ]);
    });
});

// ==================== ROUTES SPÉCIFIQUES POUR L'APP FLUTTER ====================
// Ces routes sont ajoutées pour assurer la compatibilité avec l'application Flutter
Route::middleware(['auth:sanctum'])->group(function () {
    // Route alternative pour la création d'école par distributeur (compatibilité Flutter)
    Route::post('/schools/distributor/store', [SchoolController::class, 'distributorStore']);
    
    // Route pour obtenir les communes par wilaya
    Route::get('/schools/communes/{wilaya}', [SchoolController::class, 'getCommunesByWilaya']);
    
    // Recherche d'écoles
    Route::post('/schools/search', [SchoolController::class, 'search']);
    
    // Statistiques des écoles
    Route::get('/schools/statistics', [SchoolController::class, 'statistics']);
});

// ==================== FALLBACK ====================
Route::fallback(function () {
    return response()->json([
        'error' => 'Route non trouvée',
        'message' => 'La route demandée n\'existe pas',
        'available_routes' => [
            'POST /api/login',
            'POST /api/distributor/schools',
            'POST /api/schools/distributor/store',
            'GET /api/schools',
            'GET /api/distributor/schools',
            'GET /api/distributor/dashboard',
            'POST /api/deliveries',
            'GET /api/dashboard/distributor-stats',
        ]
    ], 404);
});