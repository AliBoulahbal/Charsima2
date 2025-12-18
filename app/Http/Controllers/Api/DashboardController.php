<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\School;
use App\Models\User;
use App\Models\Distributor;
use App\Models\Kiosk;
use App\Models\CardAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
                'phone' => $user->phone ?? 'N/A',
                'wilaya' => $user->distributorProfile->wilaya ?? 'N/A',
            ]
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
    
    /**
     * Dashboard pour l'app mobile Flutter - Route: /api/dashboard/distributor-stats
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
            
            $distributor = $user->distributorProfile;
            
            if (!$distributor) {
                return response()->json([
                    'success' => false,
                    'error' => 'Profil distributeur non trouvé.'
                ], 404);
            }
            
            // === STATISTIQUES DE BASE ===
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
            
            // === STATISTIQUES DES CARTES ===
            // Total des cartes allouées au distributeur
            $totalCards = $distributor->deliveries()->sum('quantity');
            
            // Cartes livrées
            $cardAllocations = []; 
            
            // Cartes en attente de livraison
            $cardsPending = $distributor->deliveries()
                ->whereIn('status', ['pending', 'in_progress'])
                ->sum('quantity');
            
            // Cartes disponibles = total - (livrées + en attente)
            $cardsAvailable = max(0, $totalCards - ($cardsDelivered + $cardsPending));
            
            // === STATISTIQUES DES PAIEMENTS ===
            $totalPayments = $distributor->payments()->count();
            $lastPayment = $distributor->payments()
                ->orderBy('payment_date', 'desc')
                ->first();
            
            // Taux de paiement
            $paymentRate = $totalRevenue > 0 ? ($totalPaid / $totalRevenue) * 100 : 0;
            
            // === COMMANDES RÉCENTES (10 dernières) ===
            $recentDeliveries = $distributor->deliveries()
                ->with(['school:id,name,commune,wilaya,address,phone']) 
                ->orderBy('delivery_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($delivery) {
                    return [
                        'id' => $delivery->id,
                        'order_number' => 'CMD-' . str_pad($delivery->id, 6, '0', STR_PAD_LEFT),
                        'customer' => $delivery->school->name ?? 'N/A',
                        'school_name' => $delivery->school->name ?? 'N/A',
                        'address' => $delivery->school->address ?? $delivery->school->commune ?? $delivery->school->wilaya ?? '',
                        'city' => $delivery->school->wilaya ?? '',
                        'status' => $delivery->status,
                        'amount' => (float) ($delivery->final_price ?? 0),
                        'quantity' => $delivery->quantity ?? 0,
                        'date' => $delivery->delivery_date ? Carbon::parse($delivery->delivery_date)->format('d/m/Y') : 'N/A',
                        'school_id' => $delivery->school_id,
                        'status_color' => $this->getStatusColor($delivery->status),
                    ];
                });
            
            // === PAIEMENTS RÉCENTS (5 derniers) ===
            $recentPayments = $distributor->payments()
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => (float) $payment->amount,
                        'payment_date' => $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d/m/Y H:i') : 'N/A',
                        'date' => $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A',
                        'method' => $payment->method ?? 'cash',
                        'payment_method' => $payment->method ?? 'cash',
                        'reference' => $payment->reference_number,
                        'reference_number' => $payment->reference_number,
                        'delivery_id' => $payment->delivery_id,
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
            
            // === STOCK DE CARTES DÉTAILLÉ ===
            $cardAllocations = CardAllocation::where('distributor_id', $distributor->id)
                ->where('status', 'active')
                ->with('cardType')
                ->get()
                ->map(function ($allocation) {
                    return [
                        'card_type' => $allocation->cardType->name ?? 'Standard',
                        'quantity_allocated' => $allocation->quantity,
                        'quantity_used' => $allocation->quantity_used,
                        'quantity_available' => $allocation->quantity - $allocation->quantity_used,
                        'allocation_date' => $allocation->allocation_date,
                    ];
                });
            
            // === RETOUR DES DONNÉES ===
            return response()->json([
                'success' => true,
                'data' => [
                    // Statistiques de base
                    'totalOrders' => $totalDeliveries,
                    'pendingDeliveries' => $pendingDeliveries,
                    'completedToday' => $completedToday,
                    'totalRevenue' => (float) $totalRevenue,
                    'totalPaid' => (float) $totalPaid,
                    'remainingAmount' => (float) $remainingAmount,
                    
                    // Statistiques des cartes
                    'totalCards' => $totalCards,
                    'cardsDelivered' => $cardsDelivered,
                    'cardsAvailable' => $cardsAvailable,
                    'cardsPending' => $cardsPending,
                    'cardAllocations' => $cardAllocations,
                    
                    // Statistiques des paiements
                    'totalPayments' => $totalPayments,
                    'lastPaymentAmount' => $lastPayment ? (float) $lastPayment->amount : 0,
                    'lastPaymentDate' => $lastPayment ? $lastPayment->payment_date : null,
                    'paymentRate' => round($paymentRate, 2),
                    
                    // Statistiques mensuelles
                    'monthlyDeliveries' => $monthlyDeliveries,
                    'monthlyRevenue' => (float) $monthlyRevenue,
                    'assignedSchools' => $assignedSchools,
                    
                    // Données récentes
                    'recentOrders' => $recentDeliveries,
                    'recentPayments' => $recentPayments,
                    
                    // Informations distributeur
                    'distributor' => [
                        'id' => $distributor->id,
                        'name' => $distributor->name ?? $user->name,
                        'email' => $user->email,
                        'phone' => $distributor->phone ?? $user->phone ?? 'Non renseigné',
                        'wilaya' => $distributor->wilaya ?? 'Non renseigné',
                        'address' => $distributor->address ?? 'Non renseigné',
                        'commission_rate' => $distributor->commission_rate ?? 0,
                    ],
                    
                    // Résumé
                    'summary' => [
                        'deliveries' => $totalDeliveries,
                        'revenue' => number_format($totalRevenue, 2, ',', ' ') . ' DZD',
                        'due' => number_format($remainingAmount, 2, ',', ' ') . ' DZD',
                        'cards' => "$cardsDelivered/$totalCards cartes livrées",
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
     * (Version alternative pour compatibilité)
     */
    public function distributorStats(Request $request)
    {
        $user = Auth::user();
        
        $distributor = $user->distributorProfile;

        if (!$distributor) {
            return response()->json(['success' => false, 'error' => 'Profil distributeur non trouvé.'], 404);
        }
        
        // Les calculs utilisent les Accessors de votre modèle Distributor.php
        $totalDeliveries = $distributor->getTotalDeliveriesAttribute();
        $totalDeliveredAmount = $distributor->getTotalDeliveredAmountAttribute();
        $totalPaid = $distributor->getTotalPaidAmountAttribute(); 
        $remaining = $distributor->getTotalRemainingAmountAttribute();
        
        // Statistiques des cartes
        $totalCards = CardAllocation::where('distributor_id', $distributor->id)
            ->where('status', 'active')
            ->sum('quantity');
        
        $cardsDelivered = $distributor->deliveries()
            ->where('status', 'confirmed')
            ->sum('quantity');
        
        $cardsAvailable = max(0, $totalCards - $cardsDelivered);
        
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
            ->get()
            ->map(function ($delivery) {
                return [
                    'id' => $delivery->id,
                    'date' => $delivery->delivery_date ? Carbon::parse($delivery->delivery_date)->format('d/m/Y') : 'N/A',
                    'school' => $delivery->school->name ?? 'N/A',
                    'amount' => (float) $delivery->final_price,
                    'quantity' => $delivery->quantity,
                    'status' => $delivery->status,
                ];
            });
        
        $recentPayments = $distributor->payments()
            ->select('id', 'amount', 'payment_date', 'method', 'reference_number')
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => (float) $payment->amount,
                    'payment_date' => $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A',
                    'method' => $payment->method,
                    'reference_number' => $payment->reference_number,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_deliveries' => $totalDeliveries,
                'total_delivered_amount' => $totalDeliveredAmount,
                'total_paid' => $totalPaid,
                'remaining' => $remaining,
                'totalCards' => $totalCards,
                'cardsDelivered' => $cardsDelivered,
                'cardsAvailable' => $cardsAvailable,
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
     * Statistiques détaillées des cartes pour un distributeur
     */
    public function cardsStats(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;
        
        if (!$distributor) {
            return response()->json(['success' => false, 'error' => 'Profil distributeur non trouvé.'], 404);
        }
        
        // Récupérer toutes les allocations de cartes
        $allocations = CardAllocation::where('distributor_id', $distributor->id)
            ->with('cardType')
            ->get();
        
        $totalAllocated = $allocations->sum('quantity');
        $totalUsed = $allocations->sum('quantity_used');
        $totalAvailable = $totalAllocated - $totalUsed;
        
        // Statistiques par type de carte
        $cardsByType = $allocations->map(function ($allocation) {
            return [
                'type' => $allocation->cardType->name ?? 'Standard',
                'type_id' => $allocation->card_type_id,
                'allocated' => $allocation->quantity,
                'used' => $allocation->quantity_used,
                'available' => $allocation->quantity - $allocation->quantity_used,
                'allocation_date' => $allocation->allocation_date,
                'expiry_date' => $allocation->expiry_date,
            ];
        });
        
        // Historique des livraisons par mois (pour le graphique)
        $monthlyUsage = Delivery::where('distributor_id', $distributor->id)
            ->select(
                DB::raw('YEAR(delivery_date) as year'),
                DB::raw('MONTH(delivery_date) as month'),
                DB::raw('SUM(quantity) as cards_delivered')
            )
            ->where('status', 'confirmed')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($item) {
                return [
                    'period' => Carbon::create($item->year, $item->month, 1)->format('M Y'),
                    'cards_delivered' => (int) $item->cards_delivered,
                ];
            })
            ->reverse()
            ->values();
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total_allocated' => $totalAllocated,
                'total_used' => $totalUsed,
                'total_available' => $totalAvailable,
                'usage_percentage' => $totalAllocated > 0 ? round(($totalUsed / $totalAllocated) * 100, 2) : 0,
            ],
            'cards_by_type' => $cardsByType,
            'monthly_usage' => $monthlyUsage,
            'last_updated' => now()->toDateTimeString(),
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
                'color' => $this->getStatusColor($delivery->status),
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
                'color' => '#4CAF50',
            ];
        }
        
        // Récupérer les allocations de cartes récentes
        $allocations = CardAllocation::where('distributor_id', $distributor->id)
            ->with('cardType')
            ->orderBy('allocation_date', 'desc')
            ->limit(5)
            ->get();
        
        foreach ($allocations as $allocation) {
            $activities[] = [
                'type' => 'card_allocation',
                'title' => 'Allocation de cartes',
                'description' => $allocation->quantity . ' cartes ' . ($allocation->cardType->name ?? 'Standard'),
                'date' => $allocation->allocation_date,
                'icon' => 'credit_card',
                'color' => '#2196F3',
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
            
            $cardsDelivered = $distributor->deliveries()
                ->whereYear('delivery_date', $currentYear)
                ->whereMonth('delivery_date', $month)
                ->sum('quantity');
            
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
                'cards_delivered' => $cardsDelivered,
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
                'total_cards_delivered' => array_sum(array_column($months, 'cards_delivered')),
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
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return response()->json(['success' => false, 'error' => 'Accès non autorisé.'], 403);
        }
        
        // --- Statistiques globales pour l'Admin ---
        $totalDeliveries = Delivery::count();
        $totalCards = Delivery::sum('quantity');
        $totalExpected = Delivery::sum('final_price');
        $totalPaid = Payment::sum('amount');
        $remaining = $totalExpected - $totalPaid;
        
        $distributorCount = Distributor::count();
        $schoolCount = School::count();
        $kioskCount = Kiosk::count();
        
        // Statistiques des cartes
        $totalCardsAllocated = CardAllocation::sum('quantity');
        $totalCardsUsed = CardAllocation::sum('quantity_used');
        $totalCardsAvailable = $totalCardsAllocated - $totalCardsUsed;

        // Dernières livraisons
        $recentDeliveries = Delivery::with(['school', 'distributor.user'])
            ->orderBy('delivery_date', 'desc')
            ->limit(10)
            ->get();
        
        // Derniers paiements
        $recentPayments = Payment::with(['distributor.user'])
            ->orderBy('payment_date', 'desc')
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
                'total_cards_allocated' => $totalCardsAllocated,
                'total_cards_used' => $totalCardsUsed,
                'total_cards_available' => $totalCardsAvailable,
            ],
            'recent_deliveries' => $recentDeliveries,
            'recent_payments' => $recentPayments,
        ]);
    }
    
    /**
     * Vue d'ensemble pour Admin
     */
    public function overview(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return response()->json(['success' => false, 'error' => 'Accès non autorisé.'], 403);
        }
        
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
                DB::raw('SUM(quantity) as cards'),
                DB::raw('SUM(final_price) as revenue')
            )
            ->whereYear('delivery_date', now()->year)
            ->groupBy(DB::raw('MONTH(delivery_date)'))
            ->orderBy('month')
            ->get();
        
        // Distribution par wilaya
        $wilayaDistribution = Delivery::join('distributors', 'deliveries.distributor_id', '=', 'distributors.id')
            ->select('distributors.wilaya', DB::raw('COUNT(*) as deliveries'), DB::raw('SUM(deliveries.quantity) as cards'))
            ->groupBy('distributors.wilaya')
            ->orderByDesc('deliveries')
            ->get();
        
        return response()->json([
            'success' => true,
            'overview' => [
                'deliveries_by_status' => $deliveriesByStatus,
                'payments_by_method' => $paymentsByMethod,
                'monthly_trend' => $monthlyTrend,
                'wilaya_distribution' => $wilayaDistribution,
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
            
            $cards = Delivery::whereHas('distributor', function($q) use ($wilaya) {
                $q->where('wilaya', $wilaya->wilaya);
            })->sum('quantity');
            
            $revenue = Delivery::whereHas('distributor', function($q) use ($wilaya) {
                $q->where('wilaya', $wilaya->wilaya);
            })->sum('final_price');
            
            $wilaya->deliveries_count = $deliveries;
            $wilaya->total_cards = $cards;
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
                DB::raw('(SELECT COALESCE(SUM(quantity), 0) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as total_cards'),
                DB::raw('(SELECT COALESCE(SUM(final_price), 0) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as total_delivered'),
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
                'total_cards' => Delivery::selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('school_id', 'schools.id'),
                'total_delivered' => Delivery::selectRaw('COALESCE(SUM(final_price), 0)')
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
     * Statistiques de stock de cartes
     */
    public function cardsStock(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;
        
        if (!$distributor) {
            return response()->json(['success' => false, 'error' => 'Profil distributeur non trouvé.'], 404);
        }
        
        $allocations = CardAllocation::where('distributor_id', $distributor->id)
            ->with('cardType')
            ->get();
        
        $stats = [
            'total_allocated' => $allocations->sum('quantity'),
            'total_used' => $allocations->sum('quantity_used'),
            'total_available' => $allocations->sum('quantity') - $allocations->sum('quantity_used'),
            'allocations' => $allocations->map(function($allocation) {
                return [
                    'card_type' => $allocation->cardType->name ?? 'Standard',
                    'allocated' => $allocation->quantity,
                    'used' => $allocation->quantity_used,
                    'available' => $allocation->quantity - $allocation->quantity_used,
                    'allocation_date' => $allocation->allocation_date,
                    'expiry_date' => $allocation->expiry_date,
                ];
            }),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
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
            'confirmed' => '#66BB6A', // Green
            'cancelled' => '#EF5350', // Red
            'delivered' => '#66BB6A', // Green
            'paid' => '#66BB6A', // Green
        ];
        
        return $colors[$status] ?? '#9E9E9E'; // Grey par défaut
    }
}