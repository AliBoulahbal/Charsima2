<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\User;
use App\Models\School;
use App\Models\Distributor;
use App\Models\Kiosk;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Point d'entrée pour la route /admin-dashboard
     * Redirige simplement vers la logique de statistiques
     */
    public function adminDashboard(): JsonResponse
    {
        return $this->adminStats();
    }

    /**
     * Point d'entrée pour la route /admin-stats
     * Calcule et renvoie les statistiques
     */
    public function adminStats(): JsonResponse
    {
        try {
            // 1. Statistiques globales
            $totalDeliveries = Delivery::count();
            $totalCards = Delivery::sum('quantity');
            $totalExpected = Delivery::sum('total_price');
            $totalPaid = Payment::sum('amount');
            $remaining = max(0, $totalExpected - $totalPaid);

            // Vérification si le modèle Distributor existe, sinon on utilise User
            // Cette sécurité évite le crash si le modèle Distributor n'est pas créé
            if (class_exists(Distributor::class)) {
                $distributorCount = User::where('role', 'distributor')->count();
                
                // 2. Top Distributeurs (Logique complexe)
                $topDistributors = Distributor::with(['user'])
                    ->select([
                        'distributors.*',
                        DB::raw('(SELECT COUNT(*) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as deliveries_count'),
                        DB::raw('(SELECT COALESCE(SUM(total_price), 0) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as total_delivered'),
                        DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.distributor_id = distributors.id) as total_paid')
                    ])
                    ->orderByDesc('deliveries_count')
                    ->limit(10)
                    ->get()
                    ->map(function($distributor) {
                        $totalDue = ($distributor->total_delivered ?? 0) - ($distributor->total_paid ?? 0);
                        
                        return [
                            'user' => [
                                'name' => $distributor->user->name ?? $distributor->name,
                                'email' => $distributor->user->email ?? null,
                            ],
                            'wilaya' => $distributor->wilaya ?? 'N/A',
                            'deliveries_count' => (int) $distributor->deliveries_count,
                            'total_delivered' => (int) $distributor->total_delivered,
                            'total_paid' => (int) $distributor->total_paid,
                            'total_due' => max(0, $totalDue),
                        ];
                    });

                // 5. Statistiques par Wilaya
                $wilayaStats = Distributor::select('wilaya', DB::raw('COUNT(*) as distributor_count'))
                    ->whereNotNull('wilaya')
                    ->groupBy('wilaya')
                    ->orderByDesc('distributor_count')
                    ->get()
                    ->map(function($stat) {
                        return [
                            'wilaya' => $stat->wilaya,
                            'distributor_count' => (int) $stat->distributor_count,
                        ];
                    });

            } else {
                // Fallback si le modèle Distributor n'existe pas encore
                $distributorCount = User::where('role', 'distributor')->count();
                $topDistributors = [];
                $wilayaStats = [];
            }

            $schoolCount = School::count();
            $kioskCount = Kiosk::count();

            // 3. Top Écoles
            $topSchools = School::select([
                    'schools.id',
                    'schools.name',
                    'schools.wilaya',
                    DB::raw('(SELECT COUNT(*) FROM deliveries WHERE deliveries.school_id = schools.id) as deliveries_count'),
                    DB::raw('(SELECT COALESCE(SUM(total_price), 0) FROM deliveries WHERE deliveries.school_id = schools.id) as total_delivered')
                ])
                ->orderByDesc('deliveries_count')
                ->limit(10)
                ->get()
                ->map(function($school) {
                    return [
                        'name' => $school->name,
                        'wilaya' => $school->wilaya ?? 'N/A',
                        'deliveries_count' => (int) $school->deliveries_count,
                        'total_delivered' => (int) $school->total_delivered,
                    ];
                });

            // 4. Dernières Livraisons
            // On vérifie les relations pour éviter les erreurs si null
            $recentDeliveries = Delivery::with(['school', 'kiosk']) // Retiré 'distributor.user' temporairement pour sécurité
                ->orderByDesc('delivery_date')
                ->limit(10)
                ->get()
                ->map(function($delivery) {
                    // Récupération sécurisée du distributeur
                    $distributorData = null;
                    if ($delivery->relationLoaded('distributor') && $delivery->distributor) {
                        $distributorData = [
                             'user' => [
                                'name' => $delivery->distributor->name ?? 'Inconnu',
                            ],
                        ];
                    }

                    return [
                        'id' => $delivery->id,
                        'quantity' => (int) $delivery->quantity,
                        'total_price' => (int) $delivery->total_price,
                        'delivery_date' => $delivery->delivery_date,
                        'status' => $delivery->status ?? 'confirmed',
                        'school' => $delivery->school ? [
                            'name' => $delivery->school->name,
                        ] : null,
                        'distributor' => $distributorData,
                        'kiosk' => $delivery->kiosk ? [
                            'name' => $delivery->kiosk->name,
                        ] : null,
                    ];
                });

            // 6. Retourner en JSON
            $data = [
                'totalDeliveries' => (int) $totalDeliveries,
                'distributorCount' => (int) $distributorCount,
                'schoolCount' => (int) $schoolCount,
                'kioskCount' => (int) $kioskCount,
                'totalCards' => (int) $totalCards,
                'totalExpected' => (int) $totalExpected,
                'totalPaid' => (int) $totalPaid,
                'remaining' => (int) $remaining,
                'topDistributors' => $topDistributors,
                'topSchools' => $topSchools,
                'recentDeliveries' => $recentDeliveries,
                'wilayaStats' => $wilayaStats,
                'timestamp' => now()->toDateTimeString(),
                'status' => 'real_data_from_db'
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            // En cas d'erreur (Ex: Table non trouvée, colonne manquante)
            // On log l'erreur pour le debug serveur
            \Illuminate\Support\Facades\Log::error('Erreur Admin Dashboard: ' . $e->getMessage());

            return response()->json([
                'success' => true, // On renvoie true pour ne pas faire crasher Flutter
                'message' => 'Erreur partielle: ' . $e->getMessage(),
                'data' => [
                    'totalDeliveries' => 0,
                    'distributorCount' => 0,
                    'schoolCount' => 0,
                    'kioskCount' => 0,
                    'totalCards' => 0,
                    'totalExpected' => 0,
                    'totalPaid' => 0,
                    'remaining' => 0,
                    'topDistributors' => [],
                    'topSchools' => [],
                    'recentDeliveries' => [],
                    'wilayaStats' => [],
                    'timestamp' => now()->toDateTimeString(),
                    'status' => 'error_fallback'
                ]
            ]);
        }
    }
}