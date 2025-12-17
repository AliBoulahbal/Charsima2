<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\School;
use App\Models\User;
use App\Models\Distributor;
use App\Models\Kiosk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Ajout de l'import Log

class DashboardController extends Controller
{
    /**
     * Statistiques générales pour tous les utilisateurs authentifiés
     */
    public function stats(Request $request)
    {
        $user = Auth::user();
        
        $stats = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone ?? 'N/A', // Utilisation du champ 'phone' (supposé existant après migration)
            ]
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
    
    /**
     * Dashboard pour l'app mobile Flutter - Route: /api/distributor/dashboard
     */
    public function distributorDashboard(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'distributor') {
                return response()->json([
                    'success' => false,
                    'error' => 'Accès non autorisé. Rôle distributeur requis.'
                ], 403);
            }
            
            // Assurez-vous que la relation distributorProfile existe dans le modèle User
            $distributor = $user->distributorProfile;
            
            if (!$distributor) {
                return response()->json([
                    'success' => false,
                    'error' => 'Profil distributeur non trouvé.'
                ], 404);
            }
            
            // Les calculs utilisent les Accessors du modèle Distributor
            $totalDeliveries = $distributor->total_deliveries;
            $totalRevenue = $distributor->total_delivered_amount;
            $totalPaid = $distributor->total_paid_amount;
            $remainingAmount = $distributor->total_remaining_amount;
            
            // Livraisons en attente
            $pendingDeliveries = $distributor->deliveries()
                ->whereIn('status', ['pending', 'in_progress', 'pending_payment'])
                ->count();
            
            // Livraisons complétées aujourd'hui
            $completedToday = $distributor->deliveries()
                ->whereDate('delivery_date', today())
                ->where('status', 'confirmed')
                ->count();
            
            // === COMMANDES RÉCENTES (10 dernières) ===
            $recentDeliveries = $distributor->deliveries()
                // CORRECTION CLÉ: Assure que les colonnes 'address' et 'phone' sont sélectionnées si elles sont dans la DB
                ->with(['school:id,name,commune,wilaya,address,phone']) 
                ->orderBy('delivery_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($delivery) {
                    return [
                        'id' => $delivery->id,
                        'order_number' => 'CMD-' . str_pad($delivery->id, 6, '0', STR_PAD_LEFT),
                        'customer' => $delivery->school->name ?? 'N/A',
                        'address' => $delivery->school->address ?? $delivery->school->commune ?? $delivery->school->wilaya ?? '',
                        'city' => $delivery->school->wilaya ?? '', // Wilaya utilisée comme ville dans ce contexte
                        'status' => $delivery->status,
                        'amount' => (float) ($delivery->final_price ?? 0),
                        'quantity' => $delivery->quantity ?? 0,
                        'date' => $delivery->delivery_date ? Carbon::parse($delivery->delivery_date)->format('d/m/Y') : 'N/A',
                        'school_id' => $delivery->school_id,
                        'status_color' => $this->getStatusColor($delivery->status),
                    ];
                });
            
            // === STATISTIQUES MENSUELLES ===
            $currentMonth = now()->month;
            $currentYear = now()->year;
            
            $monthlyDeliveries = $distributor->deliveries()
                ->whereMonth('delivery_date', $currentMonth)
                ->whereYear('delivery_date', $currentYear)
                ->count();
            
            $monthlyRevenue = $distributor->deliveries()
                ->whereMonth('delivery_date', $currentMonth)
                ->whereYear('delivery_date', $currentYear)
                ->sum('final_price') ?? 0;
            
            // === ÉCOLES ASSIGNÉES ===
            $assignedSchools = $distributor->deliveries()
                ->distinct('school_id')
                ->count('school_id');
            
            // === RETOUR DES DONNÉES ===
            return response()->json([
                'success' => true,
                'data' => [
                    'totalOrders' => $totalDeliveries,
                    'pendingDeliveries' => $pendingDeliveries,
                    'completedToday' => $completedToday,
                    'totalRevenue' => (float) $totalRevenue,
                    'totalPaid' => (float) $totalPaid,
                    'remainingAmount' => (float) $remainingAmount,
                    'monthlyDeliveries' => $monthlyDeliveries,
                    'monthlyRevenue' => (float) $monthlyRevenue,
                    'assignedSchools' => $assignedSchools,
                    'recentOrders' => $recentDeliveries,
                    'distributor' => [
                        'id' => $distributor->id,
                        'name' => $distributor->name ?? $user->name,
                        'email' => $user->email,
                        // Utilise $user->phone si $distributor->phone est null
                        'phone' => $distributor->phone ?? $user->phone ?? 'Non renseigné', 
                        'wilaya' => $distributor->wilaya ?? 'Non renseigné',
                        'address' => $distributor->address ?? 'Non renseigné',
                    ],
                    'summary' => [
                        'deliveries' => $totalDeliveries,
                        'revenue' => number_format($totalRevenue, 2, ',', ' ') . ' DZD',
                        'due' => number_format($remainingAmount, 2, ',', ' ') . ' DZD',
                    ],
                    'stats_date' => now()->format('d/m/Y H:i'),
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error("Dashboard Distributor Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur de chargement du tableau de bord.',
                'message' => $e->getMessage(),
                'debug' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
        /**
     * Statistiques pour le Distributeur mobile - Route: /api/dashboard/distributor-stats
     */
    public function distributorStats(Request $request)
    {
        $user = Auth::user();
        
        // Assurez-vous d'avoir une relation 'distributorProfile' dans le modèle User
        $distributor = $user->distributorProfile;

        if (!$distributor) {
            return response()->json(['success' => false, 'error' => 'Profil distributeur non trouvé.'], 404);
        }
        
        // Les calculs utilisent les Accessors de votre modèle Distributor.php
        $totalDeliveries = $distributor->getTotalDeliveriesAttribute();
        $totalDeliveredAmount = $distributor->getTotalDeliveredAmountAttribute();
        $totalPaid = $distributor->getTotalPaidAmountAttribute(); 
        $remaining = $distributor->getTotalRemainingAmountAttribute();
        
        // --- Statistiques mensuelles ---
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $monthlyDeliveries = $distributor->deliveries()
            ->whereMonth('delivery_date', $currentMonth)
            ->whereYear('delivery_date', $currentYear)
            ->count();
        
        $monthlyAmount = $distributor->deliveries()
            ->whereMonth('delivery_date', $currentMonth)
            ->whereYear('delivery_date', $currentYear)
            ->sum('final_price');
        
        $schoolsServed = $distributor->deliveries()
            ->distinct('school_id')
            ->count('school_id');
        
        // --- Flux d'activité ---
        $recentDeliveries = $distributor->deliveries()
            ->with(['school:id,name', 'kiosk:id,name']) 
            ->select('id', 'delivery_date', 'final_price', 'quantity', 'status', 'school_id', 'kiosk_id')
            ->orderBy('delivery_date', 'desc')
            ->limit(10)
            ->get();
        
        $recentPayments = $distributor->payments()
            ->select('id', 'amount', 'payment_date', 'method', 'reference_number')
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total_deliveries' => $totalDeliveries,
                'total_delivered_amount' => $totalDeliveredAmount,
                'total_paid' => $totalPaid,
                'remaining' => $remaining,
                'monthly_deliveries' => $monthlyDeliveries,
                'monthly_amount' => $monthlyAmount,
                'schools_served' => $schoolsServed,
                'distributor_name' => $distributor->name,
            ],
            'recent_deliveries' => $recentDeliveries,
            'recent_payments' => $recentPayments,
        ]);
    }
    
    /**
     * Activité personnelle du distributeur
     */
    public function myActivity(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;
        
        if (!$distributor) {
            return response()->json(['success' => false, 'error' => 'Profil distributeur non trouvé.'], 404);
        }
        
        $activities = [];
        
        // Récupérer les livraisons récentes
        $deliveries = $distributor->deliveries()
            ->with('school')
            ->orderBy('delivery_date', 'desc')
            ->limit(20)
            ->get();
        
        foreach ($deliveries as $delivery) {
            $activities[] = [
                'type' => 'delivery',
                'title' => 'Livraison à ' . ($delivery->school->name ?? 'École'),
                'description' => $delivery->quantity . ' cartes - ' . number_format($delivery->final_price, 2) . ' DZD',
                'date' => $delivery->delivery_date,
                'status' => $delivery->status,
                'icon' => 'local_shipping',
            ];
        }
        
        // Récupérer les paiements récents
        $payments = $distributor->payments()
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();
        
        foreach ($payments as $payment) {
            $activities[] = [
                'type' => 'payment',
                'title' => 'Paiement reçu',
                'description' => number_format($payment->amount, 2) . ' DZD - ' . $payment->method,
                'date' => $payment->payment_date,
                'reference' => $payment->reference_number,
                'icon' => 'payments',
            ];
        }
        
        // Trier par date
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        // Limiter à 15 activités
        $activities = array_slice($activities, 0, 15);
        
        return response()->json([
            'success' => true,
            'activities' => $activities,
            'total' => count($activities)
        ]);
    }
    
    /**
     * Résumé mensuel
     */
    public function monthlySummary(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;
        
        if (!$distributor) {
            return response()->json(['success' => false, 'error' => 'Profil distributeur non trouvé.'], 404);
        }
        
        $months = [];
        $currentYear = now()->year;
        
        for ($month = 1; $month <= 12; $month++) {
            $deliveriesCount = $distributor->deliveries()
                ->whereYear('delivery_date', $currentYear)
                ->whereMonth('delivery_date', $month)
                ->count();
            
            $revenue = $distributor->deliveries()
                ->whereYear('delivery_date', $currentYear)
                ->whereMonth('delivery_date', $month)
                ->sum('final_price') ?? 0;
            
            $payments = $distributor->payments()
                ->whereYear('payment_date', $currentYear)
                ->whereMonth('payment_date', $month)
                ->sum('amount') ?? 0;
            
            $months[] = [
                'month' => $month,
                'month_name' => Carbon::create()->month($month)->locale('fr')->monthName,
                'deliveries' => $deliveriesCount,
                'revenue' => (float) $revenue,
                'payments' => (float) $payments,
                'balance' => (float) ($revenue - $payments),
            ];
        }
        
        return response()->json([
            'success' => true,
            'year' => $currentYear,
            'months' => $months,
            'summary' => [
                'total_deliveries' => array_sum(array_column($months, 'deliveries')),
                'total_revenue' => array_sum(array_column($months, 'revenue')),
                'total_payments' => array_sum(array_column($months, 'payments')),
                'total_balance' => array_sum(array_column($months, 'balance')),
            ]
        ]);
    }
    
    /**
     * Statistiques pour l'Admin
     */
    public function adminStats(Request $request)
    {
        // --- Statistiques globales pour l'Admin ---
        $totalDeliveries = Delivery::count();
        $totalCards = Delivery::sum('quantity');
        $totalExpected = Delivery::sum('total_price');
        $totalPaid = Payment::sum('amount');
        $remaining = $totalExpected - $totalPaid;
        
        $distributorCount = Distributor::count();
        $schoolCount = School::count();
        $kioskCount = Kiosk::count();

        // Dernières livraisons
        $recentDeliveries = Delivery::with(['school', 'distributor.user'])
            ->orderBy('delivery_date', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json([
            'success' => true,
            'stats' => [
                'total_deliveries' => $totalDeliveries,
                'total_cards' => $totalCards,
                'total_expected' => $totalExpected,
                'total_paid' => $totalPaid,
                'remaining' => $remaining,
                'distributor_count' => $distributorCount,
                'school_count' => $schoolCount,
                'kiosk_count' => $kioskCount,
            ],
            'recent_deliveries' => $recentDeliveries,
        ]);
    }
    
    /**
     * Vue d'ensemble pour Admin
     */
    public function overview(Request $request)
    {
        // Statistiques par statut
        $deliveriesByStatus = Delivery::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Statistiques par méthode de paiement
        $paymentsByMethod = Payment::select('method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('method')
            ->get();
        
        // Évolution mensuelle
        $monthlyTrend = Delivery::select(
                DB::raw('MONTH(delivery_date) as month'),
                DB::raw('COUNT(*) as deliveries'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->whereYear('delivery_date', now()->year)
            ->groupBy(DB::raw('MONTH(delivery_date)'))
            ->orderBy('month')
            ->get();
        
        return response()->json([
            'success' => true,
            'overview' => [
                'deliveries_by_status' => $deliveriesByStatus,
                'payments_by_method' => $paymentsByMethod,
                'monthly_trend' => $monthlyTrend,
            ]
        ]);
    }
    
    /**
     * Statistiques par Wilaya
     */
    public function wilayaStats(Request $request)
    {
        $wilayaStats = Distributor::select('wilaya', DB::raw('COUNT(*) as distributor_count'))
            ->groupBy('wilaya')
            ->orderByDesc('distributor_count')
            ->get();
        
        // Ajouter les livraisons par wilaya
        foreach ($wilayaStats as $wilaya) {
            $deliveries = Delivery::whereHas('distributor', function($q) use ($wilaya) {
                $q->where('wilaya', $wilaya->wilaya);
            })->count();
            
            $revenue = Delivery::whereHas('distributor', function($q) use ($wilaya) {
                $q->where('wilaya', $wilaya->wilaya);
            })->sum('total_price');
            
            $wilaya->deliveries_count = $deliveries;
            $wilaya->total_revenue = $revenue ?? 0;
        }
        
        return response()->json([
            'success' => true,
            'wilayas' => $wilayaStats,
            'total_wilayas' => $wilayaStats->count()
        ]);
    }
    
    /**
     * Top Distributeurs
     */
    public function topDistributors(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $topDistributors = Distributor::with(['user', 'deliveries'])
            ->select([
                'distributors.*',
                DB::raw('(SELECT COUNT(*) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as deliveries_count'),
                DB::raw('(SELECT COALESCE(SUM(total_price), 0) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as total_delivered'),
                DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.distributor_id = distributors.id) as total_paid')
            ])
            ->orderByDesc('deliveries_count')
            ->limit($limit)
            ->get()
            ->map(function($distributor) {
                $distributor->total_due = ($distributor->total_delivered ?? 0) - ($distributor->total_paid ?? 0);
                return $distributor;
            });
        
        return response()->json([
            'success' => true,
            'distributors' => $topDistributors
        ]);
    }
    
    /**
     * Top Écoles
     */
    public function topSchools(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $topSchools = School::withCount('deliveries')
            ->addSelect([
                'total_delivered' => Delivery::selectRaw('COALESCE(SUM(total_price), 0)')
                    ->whereColumn('school_id', 'schools.id')
            ])
            ->orderByDesc('deliveries_count')
            ->limit($limit)
            ->get();
        
        return response()->json([
            'success' => true,
            'schools' => $topSchools
        ]);
    }
    
    /**
     * Liste des wilayas (pour les formulaires)
     */
    public function getWilayas(Request $request)
    {
        // Liste des wilayas algériennes
        $wilayas = [
            'Adrar', 'Chlef', 'Laghouat', 'Oum El Bouaghi', 'Batna', 'Béjaïa', 'Biskra', 'Béchar', 'Blida',
            'Bouira', 'Tamanrasset', 'Tébessa', 'Tlemcen', 'Tiaret', 'Tizi Ouzou', 'Alger', 'Djelfa',
            'Jijel', 'Sétif', 'Saïda', 'Skikda', 'Sidi Bel Abbès', 'Annaba', 'Guelma', 'Constantine',
            'Médéa', 'Mostaganem', 'M\'Sila', 'Mascara', 'Ouargla', 'Oran', 'El Bayadh', 'Illizi',
            'Bordj Bou Arréridj', 'Boumerdès', 'El Tarf', 'Tindouf', 'Tissemsilt', 'El Oued', 'Khenchela',
            'Souk Ahras', 'Tipaza', 'Mila', 'Aïn Defla', 'Naâma', 'Aïn Témouchent', 'Ghardaïa', 'Relizane'
        ];
        
        return response()->json([
            'success' => true,
            'wilayas' => $wilayas
        ]);
    }
    
    /**
     * Helper: Get status color
     */
    private function getStatusColor($status)
    {
        $colors = [
            'pending' => '#FFA726', // Orange
            'in_progress' => '#29B6F6', // Blue
            'completed' => '#66BB6A', // Green
            'cancelled' => '#EF5350', // Red
            'delivered' => '#66BB6A', // Green
            'paid' => '#66BB6A', // Green
        ];
        
        return $colors[$status] ?? '#9E9E9E'; // Grey par défaut
    }
}