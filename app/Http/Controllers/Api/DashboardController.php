<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Tableau de bord administrateur
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé. Réservé aux administrateurs.'
                ], 403);
            }

            $period = $request->get('period', 'month'); // day, week, month, year

            // Statistiques générales
            $stats = [
                'total_schools' => School::count(),
                'total_distributors' => Distributor::count(),
                'total_users' => User::count(),
                'total_deliveries' => Delivery::count(),
                'total_payments' => Payment::count(),
                'total_revenue' => Delivery::sum('final_price'),
                'total_collected' => Payment::sum('amount'),
                'pending_balance' => Delivery::sum('final_price') - Payment::sum('amount'),
            ];

            // Statistiques par période
            $periodStats = $this->getPeriodStats($period);

            // Croissance (comparaison avec période précédente)
            $growthStats = $this->getGrowthStats($period);

            // Top distributeurs
            $topDistributors = Distributor::with('user')
                ->withCount(['deliveries', 'payments'])
                ->withSum('deliveries', 'final_price')
                ->withSum('payments', 'amount')
                ->orderByDesc('deliveries_sum_final_price')
                ->limit(10)
                ->get()
                ->map(function ($distributor) {
                    return [
                        'id' => $distributor->id,
                        'name' => $distributor->user->name ?? $distributor->name,
                        'wilaya' => $distributor->wilaya,
                        'total_deliveries' => $distributor->deliveries_count,
                        'total_revenue' => $distributor->deliveries_sum_final_price ?? 0,
                        'total_paid' => $distributor->payments_sum_amount ?? 0,
                        'balance' => ($distributor->deliveries_sum_final_price ?? 0) - ($distributor->payments_sum_amount ?? 0),
                        'payment_rate' => $distributor->deliveries_sum_final_price > 0 
                            ? round(($distributor->payments_sum_amount / $distributor->deliveries_sum_final_price) * 100, 2)
                            : 0,
                    ];
                });

            // Top écoles
            $topSchools = School::withCount('deliveries')
                ->withSum('deliveries', 'final_price')
                ->orderByDesc('deliveries_sum_final_price')
                ->limit(10)
                ->get()
                ->map(function ($school) {
                    return [
                        'id' => $school->id,
                        'name' => $school->name,
                        'wilaya' => $school->wilaya,
                        'total_deliveries' => $school->deliveries_count,
                        'total_revenue' => $school->deliveries_sum_final_price ?? 0,
                        'manager' => $school->manager_name,
                        'student_count' => $school->student_count,
                    ];
                });

            // Activité récente
            $recentActivity = $this->getRecentActivity();

            // Statistiques par wilaya
            $wilayaStats = Delivery::select(
                'wilaya',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(quantity) as cards_count'),
                DB::raw('SUM(final_price) as revenue'),
                DB::raw('SUM(paid_amount) as collected')
            )
            ->groupBy('wilaya')
            ->orderByDesc('revenue')
            ->get();

            // Tendances mensuelles
            $monthlyTrends = $this->getMonthlyTrends();

            return response()->json([
                'success' => true,
                'data' => [
                    'overview_stats' => $stats,
                    'period_stats' => $periodStats,
                    'growth_stats' => $growthStats,
                    'top_distributors' => $topDistributors,
                    'top_schools' => $topSchools,
                    'recent_activity' => $recentActivity,
                    'wilaya_stats' => $wilayaStats,
                    'monthly_trends' => $monthlyTrends,
                    'summary' => [
                        'period' => $period,
                        'last_updated' => now()->toDateTimeString(),
                        'user' => [
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $user->role,
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de chargement du tableau de bord admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques par période
     */
    private function getPeriodStats($period)
    {
        $query = Delivery::query();
        $paymentQuery = Payment::query();

        switch ($period) {
            case 'day':
                $query->whereDate('delivery_date', Carbon::today());
                $paymentQuery->whereDate('payment_date', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('delivery_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $paymentQuery->whereBetween('payment_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('delivery_date', Carbon::now()->month)
                    ->whereYear('delivery_date', Carbon::now()->year);
                $paymentQuery->whereMonth('payment_date', Carbon::now()->month)
                    ->whereYear('payment_date', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('delivery_date', Carbon::now()->year);
                $paymentQuery->whereYear('payment_date', Carbon::now()->year);
                break;
        }

        return [
            'deliveries_count' => $query->count(),
            'deliveries_revenue' => $query->sum('final_price'),
            'cards_delivered' => $query->sum('quantity'),
            'payments_count' => $paymentQuery->count(),
            'payments_amount' => $paymentQuery->sum('amount'),
            'new_schools' => School::whereBetween('created_at', $this->getDateRange($period))->count(),
            'new_distributors' => Distributor::whereBetween('created_at', $this->getDateRange($period))->count(),
        ];
    }

    /**
     * Calculer la croissance
     */
    private function getGrowthStats($period)
    {
        $currentPeriod = $this->getPeriodStats($period);
        $previousPeriod = $this->getPreviousPeriodStats($period);

        $growth = [];
        foreach ($currentPeriod as $key => $currentValue) {
            $previousValue = $previousPeriod[$key] ?? 0;
            if ($previousValue > 0) {
                $growth[$key] = round((($currentValue - $previousValue) / $previousValue) * 100, 2);
            } else {
                $growth[$key] = $currentValue > 0 ? 100 : 0;
            }
        }

        return $growth;
    }

    /**
     * Obtenir les stats de la période précédente
     */
    private function getPreviousPeriodStats($period)
    {
        $query = Delivery::query();
        $paymentQuery = Payment::query();

        switch ($period) {
            case 'day':
                $query->whereDate('delivery_date', Carbon::yesterday());
                $paymentQuery->whereDate('payment_date', Carbon::yesterday());
                break;
            case 'week':
                $query->whereBetween('delivery_date', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ]);
                $paymentQuery->whereBetween('payment_date', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereMonth('delivery_date', Carbon::now()->subMonth()->month)
                    ->whereYear('delivery_date', Carbon::now()->subMonth()->year);
                $paymentQuery->whereMonth('payment_date', Carbon::now()->subMonth()->month)
                    ->whereYear('payment_date', Carbon::now()->subMonth()->year);
                break;
            case 'year':
                $query->whereYear('delivery_date', Carbon::now()->subYear()->year);
                $paymentQuery->whereYear('payment_date', Carbon::now()->subYear()->year);
                break;
        }

        return [
            'deliveries_count' => $query->count(),
            'deliveries_revenue' => $query->sum('final_price'),
            'cards_delivered' => $query->sum('quantity'),
            'payments_count' => $paymentQuery->count(),
            'payments_amount' => $paymentQuery->sum('amount'),
            'new_schools' => School::whereBetween('created_at', $this->getDateRange($period, true))->count(),
            'new_distributors' => Distributor::whereBetween('created_at', $this->getDateRange($period, true))->count(),
        ];
    }

    /**
     * Obtenir la plage de dates
     */
    private function getDateRange($period, $previous = false)
    {
        $now = $previous ? Carbon::now()->subMonth() : Carbon::now();
        
        switch ($period) {
            case 'day':
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
            case 'week':
                return [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];
            case 'month':
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
            case 'year':
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
            default:
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
        }
    }

    /**
     * Activité récente
     */
    private function getRecentActivity()
    {
        $activities = collect();

        // Dernières livraisons
        $recentDeliveries = Delivery::with(['school:id,name', 'distributor.user:id,name'])
            ->latest('delivery_date')
            ->limit(5)
            ->get()
            ->map(function ($delivery) {
                return [
                    'type' => 'delivery',
                    'title' => 'Nouvelle livraison',
                    'description' => "{$delivery->quantity} cartes à {$delivery->school->name}",
                    'amount' => $delivery->final_price,
                    'user' => $delivery->distributor->user->name ?? 'Distributeur',
                    'date' => $delivery->delivery_date,
                    'icon' => 'local_shipping',
                    'color' => 'blue',
                ];
            });

        $activities = $activities->merge($recentDeliveries);

        // Derniers paiements
        $recentPayments = Payment::with(['delivery.school:id,name', 'distributor.user:id,name'])
            ->latest('payment_date')
            ->limit(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'payment',
                    'title' => 'Paiement reçu',
                    'description' => "{$payment->amount} DZD pour {$payment->delivery->school->name}",
                    'amount' => $payment->amount,
                    'user' => $payment->distributor->user->name ?? 'Distributeur',
                    'date' => $payment->payment_date,
                    'icon' => 'payments',
                    'color' => 'green',
                ];
            });

        $activities = $activities->merge($recentPayments);

        // Nouvelles écoles
        $recentSchools = School::latest()
            ->limit(5)
            ->get()
            ->map(function ($school) {
                return [
                    'type' => 'school',
                    'title' => 'Nouvelle école',
                    'description' => "{$school->name} à {$school->wilaya}",
                    'amount' => null,
                    'user' => $school->manager_name,
                    'date' => $school->created_at,
                    'icon' => 'school',
                    'color' => 'purple',
                ];
            });

        $activities = $activities->merge($recentSchools);

        return $activities->sortByDesc('date')->values()->take(10);
    }

    /**
     * Tendances mensuelles
     */
    private function getMonthlyTrends()
    {
        $currentYear = Carbon::now()->year;
        
        $monthlyData = Delivery::select(
            DB::raw('MONTH(delivery_date) as month'),
            DB::raw('COUNT(*) as deliveries_count'),
            DB::raw('SUM(final_price) as revenue'),
            DB::raw('SUM(quantity) as cards_count')
        )
        ->whereYear('delivery_date', $currentYear)
        ->groupBy(DB::raw('MONTH(delivery_date)'))
        ->orderBy('month')
        ->get()
        ->keyBy('month');

        $monthlyPayments = Payment::select(
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('SUM(amount) as payments_amount')
        )
        ->whereYear('payment_date', $currentYear)
        ->groupBy(DB::raw('MONTH(payment_date)'))
        ->orderBy('month')
        ->get()
        ->keyBy('month');

        $trends = [];
        for ($month = 1; $month <= 12; $month++) {
            $trends[] = [
                'month' => $month,
                'month_name' => Carbon::create()->month($month)->format('M'),
                'deliveries' => $monthlyData->get($month)->deliveries_count ?? 0,
                'revenue' => $monthlyData->get($month)->revenue ?? 0,
                'cards' => $monthlyData->get($month)->cards_count ?? 0,
                'payments' => $monthlyPayments->get($month)->payments_amount ?? 0,
            ];
        }

        return $trends;
    }

    /**
     * Rapport détaillé
     */
    public function detailedReport(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }

            $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
            $wilaya = $request->get('wilaya');
            $distributorId = $request->get('distributor_id');

            $query = Delivery::query();
            $paymentQuery = Payment::query();

            // Filtres
            $query->whereBetween('delivery_date', [$startDate, $endDate]);
            $paymentQuery->whereBetween('payment_date', [$startDate, $endDate]);

            if ($wilaya) {
                $query->where('wilaya', $wilaya);
                $paymentQuery->where('wilaya', $wilaya);
            }

            if ($distributorId) {
                $query->where('distributor_id', $distributorId);
                $paymentQuery->where('distributor_id', $distributorId);
            }

            // Données détaillées
            $deliveries = $query->with(['school', 'distributor.user'])
                ->orderBy('delivery_date', 'desc')
                ->paginate($request->get('per_page', 50));

            $summary = [
                'total_deliveries' => $query->count(),
                'total_revenue' => $query->sum('final_price'),
                'total_cards' => $query->sum('quantity'),
                'total_payments' => $paymentQuery->count(),
                'total_collected' => $paymentQuery->sum('amount'),
                'balance' => $query->sum('final_price') - $paymentQuery->sum('amount'),
                'avg_delivery_value' => $query->count() > 0 ? $query->sum('final_price') / $query->count() : 0,
                'avg_payment_value' => $paymentQuery->count() > 0 ? $paymentQuery->sum('amount') / $paymentQuery->count() : 0,
            ];

            return response()->json([
                'success' => true,
                'report' => [
                    'period' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ],
                    'summary' => $summary,
                    'deliveries' => $deliveries,
                    'filters' => [
                        'wilaya' => $wilaya,
                        'distributor_id' => $distributorId,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de génération du rapport: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques en temps réel
     */
    public function realTimeStats()
    {
        try {
            $user = Auth::user();
            
            if (!$user || !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }

            $today = Carbon::today();
            $yesterday = Carbon::yesterday();
            $thisWeekStart = Carbon::now()->startOfWeek();
            $thisMonthStart = Carbon::now()->startOfMonth();

            $stats = [
                'today' => [
                    'deliveries' => Delivery::whereDate('delivery_date', $today)->count(),
                    'revenue' => Delivery::whereDate('delivery_date', $today)->sum('final_price'),
                    'payments' => Payment::whereDate('payment_date', $today)->sum('amount'),
                    'new_schools' => School::whereDate('created_at', $today)->count(),
                ],
                'yesterday' => [
                    'deliveries' => Delivery::whereDate('delivery_date', $yesterday)->count(),
                    'revenue' => Delivery::whereDate('delivery_date', $yesterday)->sum('final_price'),
                    'payments' => Payment::whereDate('payment_date', $yesterday)->sum('amount'),
                ],
                'this_week' => [
                    'deliveries' => Delivery::whereBetween('delivery_date', [$thisWeekStart, $today])->count(),
                    'revenue' => Delivery::whereBetween('delivery_date', [$thisWeekStart, $today])->sum('final_price'),
                ],
                'this_month' => [
                    'deliveries' => Delivery::whereBetween('delivery_date', [$thisMonthStart, $today])->count(),
                    'revenue' => Delivery::whereBetween('delivery_date', [$thisMonthStart, $today])->sum('final_price'),
                    'new_distributors' => Distributor::whereBetween('created_at', [$thisMonthStart, $today])->count(),
                ],
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'timestamp' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de chargement des stats temps réel: ' . $e->getMessage()
            ], 500);
        }
    }
}