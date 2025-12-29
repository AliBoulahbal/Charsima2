<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DistributorDashboardController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\ApiDeliveryController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes publiques (sans authentification)
Route::prefix('v1')->group(function () {
    // Authentification
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    // Test de connexion API
    Route::get('/test', function() {
        return response()->json([
            'success' => true,
            'message' => 'API Laravel fonctionne',
            'timestamp' => now()->toDateTimeString(),
            'version' => '1.0'
        ]);
    });
    
    // Health check
    Route::get('/health', function() {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString()
        ]);
    });
});

// Routes protégées (avec authentification Sanctum)
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    
    // ========== AUTHENTIFICATION ==========
    Route::prefix('auth')->group(function () {
        Route::get('/user', [AuthController::class, 'user'])->name('api.user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.refresh');
    });
    
    // ========== DASHBOARDS ==========
    Route::prefix('dashboard')->group(function () {
        // Dashboard distributeur
        Route::get('/distributor-stats', [DistributorDashboardController::class, 'index'])
            ->name('dashboard.distributor-stats');
        
        // Dashboard admin
        Route::get('/admin-stats', [AdminDashboardController::class, 'adminStats'])
            ->name('dashboard.admin-stats');
        Route::get('/admin-dashboard', [AdminDashboardController::class, 'adminDashboard'])
            ->name('dashboard.admin-dashboard');
        
        // Statistiques générales
        Route::get('/overview', [AdminDashboardController::class, 'overview'])
            ->name('dashboard.overview');
    });
    
    // ========== ÉCOLES ==========
    Route::prefix('schools')->group(function () {
        Route::get('/', [SchoolController::class, 'index'])->name('schools.index');
        Route::post('/', [SchoolController::class, 'store'])->name('schools.store');
        Route::post('/distributor', [SchoolController::class, 'distributorStore'])->name('schools.distributor.store');
        Route::get('/wilayas', [SchoolController::class, 'getWilayas'])->name('schools.wilayas');
        Route::get('/communes/{wilaya}', [SchoolController::class, 'getCommunesByWilaya'])->name('schools.communes');
        
        // Routes pour une école spécifique
        Route::prefix('{school}')->group(function () {
            Route::get('/', [SchoolController::class, 'show'])->name('schools.show');
            Route::put('/', [SchoolController::class, 'update'])->name('schools.update');
            Route::delete('/', [SchoolController::class, 'destroy'])->name('schools.destroy');
        });
    });
    
    // ========== LIVRAISONS ==========
    Route::prefix('deliveries')->group(function () {
        Route::get('/', [ApiDeliveryController::class, 'index'])->name('deliveries.index');
        Route::get('/raw', [ApiDeliveryController::class, 'raw'])->name('deliveries.raw');
        Route::post('/', [ApiDeliveryController::class, 'storeWithLocation'])->name('deliveries.store');
        Route::get('/stats', [ApiDeliveryController::class, 'getStats'])->name('deliveries.stats');
        
        // Routes pour distributeurs
        Route::prefix('distributor')->group(function () {
            Route::get('/', [ApiDeliveryController::class, 'distributorDeliveries'])->name('deliveries.distributor.index');
            Route::get('/stats', [ApiDeliveryController::class, 'distributorStats'])->name('deliveries.distributor.stats');
        });
        
        // Routes pour une livraison spécifique
        Route::prefix('{delivery}')->group(function () {
            Route::get('/', [ApiDeliveryController::class, 'show'])->name('deliveries.show');
            Route::put('/', [ApiDeliveryController::class, 'update'])->name('deliveries.update');
            Route::delete('/', [ApiDeliveryController::class, 'destroy'])->name('deliveries.destroy');
            Route::post('/confirm', [ApiDeliveryController::class, 'confirm'])->name('deliveries.confirm');
            Route::post('/cancel', [ApiDeliveryController::class, 'cancel'])->name('deliveries.cancel');
        });
    });
    
    // ========== PAIEMENTS ==========
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
        
        // Routes pour distributeurs
        Route::prefix('distributor')->group(function () {
            Route::get('/', [PaymentController::class, 'distributorPayments'])->name('payments.distributor.index');
            Route::get('/stats', [PaymentController::class, 'distributorStats'])->name('payments.distributor.stats');
        });
        
        // Routes pour un paiement spécifique
        Route::prefix('{payment}')->group(function () {
            Route::get('/', [PaymentController::class, 'show'])->name('payments.show');
            Route::put('/', [PaymentController::class, 'update'])->name('payments.update');
            Route::delete('/', [PaymentController::class, 'destroy'])->name('payments.destroy');
            Route::post('/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
            Route::post('/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
        });
    });
    
    // ========== UTILISATEURS ==========
    Route::prefix('users')->middleware('can:viewAny,App\Models\User')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        
        // Routes pour un utilisateur spécifique
        Route::prefix('{user}')->group(function () {
            Route::get('/', [UserController::class, 'show'])->name('users.show');
            Route::put('/', [UserController::class, 'update'])->name('users.update');
            Route::delete('/', [UserController::class, 'destroy'])->name('users.destroy');
        });
    });
    
    // ========== PROFILE UTILISATEUR ==========
    Route::prefix('profile')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('profile.show');
        Route::put('/', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [UserController::class, 'updatePassword'])->name('profile.password');
    });
    
    // ========== DISTRIBUTEURS ==========
    Route::prefix('distributors')->group(function () {
        Route::get('/', [UserController::class, 'distributors'])->name('distributors.index');
        Route::get('/stats', [DistributorDashboardController::class, 'distributorStats'])->name('distributors.stats');
        
        // Routes pour un distributeur spécifique
        Route::prefix('{distributor}')->group(function () {
            Route::get('/', [UserController::class, 'showDistributor'])->name('distributors.show');
            Route::put('/', [UserController::class, 'updateDistributor'])->name('distributors.update');
            Route::get('/deliveries', [ApiDeliveryController::class, 'deliveriesByDistributor'])->name('distributors.deliveries');
            Route::get('/payments', [PaymentController::class, 'paymentsByDistributor'])->name('distributors.payments');
        });
    });
    
    // ========== RAPPORTS ==========
    Route::prefix('reports')->group(function () {
        Route::get('/deliveries', [ApiDeliveryController::class, 'report'])->name('reports.deliveries');
        Route::get('/payments', [PaymentController::class, 'report'])->name('reports.payments');
        Route::get('/schools', [SchoolController::class, 'report'])->name('reports.schools');
        Route::get('/distributors', [UserController::class, 'distributorReport'])->name('reports.distributors');
    });
    
    // ========== KIOSQUES ==========
    Route::prefix('kiosks')->group(function () {
        Route::get('/', [SchoolController::class, 'kiosks'])->name('kiosks.index');
        Route::post('/', [SchoolController::class, 'storeKiosk'])->name('kiosks.store');
        
        // Routes pour un kiosque spécifique
        Route::prefix('{kiosk}')->group(function () {
            Route::get('/', [SchoolController::class, 'showKiosk'])->name('kiosks.show');
            Route::put('/', [SchoolController::class, 'updateKiosk'])->name('kiosks.update');
            Route::delete('/', [SchoolController::class, 'destroyKiosk'])->name('kiosks.destroy');
        });
    });
});

// ========== FALLBACK ROUTE ==========
// Route pour les URLs non trouvées
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route non trouvée. Vérifiez l\'URL et la méthode HTTP.',
        'documentation' => 'Consultez la documentation de l\'API pour la liste des routes disponibles.'
    ], 404);
});

// ========== DEBUG ROUTES (à désactiver en production) ==========
if (env('APP_DEBUG', false)) {
    Route::prefix('debug')->group(function () {
        Route::get('/routes', function () {
            $routes = collect(Route::getRoutes()->getRoutes())
                ->map(function ($route) {
                    return [
                        'method' => implode('|', $route->methods()),
                        'uri' => $route->uri(),
                        'name' => $route->getName(),
                        'action' => $route->getActionName(),
                    ];
                });
            
            return response()->json($routes);
        });
        
        Route::get('/env', function () {
            return response()->json([
                'app_env' => env('APP_ENV'),
                'app_debug' => env('APP_DEBUG'),
                'app_url' => env('APP_URL'),
                'sanctum_stateful' => env('SANCTUM_STATEFUL_DOMAINS'),
                'cors_paths' => config('cors.paths', []),
            ]);
        });
        
        Route::get('/test-auth', function () {
            return response()->json([
                'authenticated' => auth()->check(),
                'user' => auth()->check() ? [
                    'id' => auth()->id(),
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'role' => auth()->user()->role,
                ] : null,
                'token_valid' => request()->bearerToken() ? true : false,
            ]);
        })->middleware('auth:sanctum');
    });


    // Dans la section routes publiques (avant le middleware)
Route::prefix('v1')->group(function () {
    // ... routes existantes ...
    
    // AJOUTEZ CES ROUTES DE DEBUG
    Route::get('/debug-auth', function (Request $request) {
        return response()->json([
            'timestamp' => now()->toDateTimeString(),
            'bearer_token_received' => $request->bearerToken(),
            'token_parts' => $request->bearerToken() ? explode('|', $request->bearerToken()) : null,
            'auth_guard_check' => auth()->guard('sanctum')->check(),
            'user_via_guard' => auth()->guard('sanctum')->user(),
            'headers_received' => [
                'authorization' => $request->header('Authorization'),
                'accept' => $request->header('Accept'),
                'content-type' => $request->header('Content-Type'),
            ],
        ]);
    });
    
    Route::get('/debug-auth-protected', function (Request $request) {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->user(),
            'token_valid' => $request->bearerToken() ? true : false,
            'sanctum_working' => true,
        ]);
    })->middleware('auth:sanctum');
});
}
