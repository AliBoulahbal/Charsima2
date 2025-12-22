<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\School;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DistributorDashboardController extends Controller
{
    /**
     * Tableau de bord distributeur
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'distributor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé. Réservé aux distributeurs.'
                ], 403);
            }

            $distributor = $user->distributorProfile;
            
            if (!$distributor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil distributeur non trouvé.'
                ], 404);
            }

            $period = $request->get('period', 'month'); // day, week, month, year

            // Statistiques principales
            $stats = [
                'total_cards' => $this->getTotalCards($distributor),
                'cards_delivered' => $this->getCardsDelivered($distributor),
                'cards_available' => $this->getCardsAvailable($distributor),
                'cards_pending' => $this->getCardsPending($distributor),
                'total_paid' => $this->getTotalPaid($distributor),
                'remaining' => $this->getRemaining($distributor),
                'payment_rate' => $this->getPaymentRate($distributor),
                'total_delivered_amount' => $this->getTotalDeliveredAmount($distributor),
                'schools_served' => $this->getSchoolsServed($distributor),
            ];

            // Statistiques par période
            $periodStats = $this->getDistributorPeriodStats($distributor, $period);

            // Livraisons récentes
            $recentDeliveries = $this->getRecentDeliveries($distributor);

            // Paiements récents
            $recentPayments = $this->getRecentPayments($distributor);

            // Écoles récentes
            $recentSchools = $this->getRecentSchools($distributor);

            // Prévisions
            $forecast = $this->getForecast($distributor);

            return response()->json([
                'success' => true,
                'data' => [
                    'main_stats' => $stats,
                    'period_stats' => $periodStats,
                    'recent_deliveries' => $recentDeliveries,
                    'recent_payments' => $recentPayments,
                    'recent_schools' => $recentSchools,
                    'forecast' => $forecast,
                    'distributor_info' => [
                        'id' => $distributor->id,
                        'name' => $distributor->name ?? $user->name,
                        'email' => $user->email,
                        'phone' => $distributor->phone ?? $user->phone,
                        'wilaya' => $distributor->wilaya,
                        'created_at' => $distributor->created_at,
                        'status' => 'active',
                    ],
                    'summary' => [
                        'period' => $period,
                        'last_updated' => now()->toDateTimeString(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de chargement du tableau de bord: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Méthodes helper pour les statistiques
     */
    private function getTotalCards($distributor)
    {
        return $distributor->deliveries()->sum('quantity');
    }

    private function getCardsDelivered($distributor)
    {
        return $distributor->deliveries()
            ->where('status', 'completed')
            ->sum('quantity');
    }

    private function getCardsAvailable($distributor)
    {
        $total = $this->getTotalCards($distributor);
        $delivered = $this->getCardsDelivered($distributor);
        $pending = $this->getCardsPending($distributor);
        return max(0, $total - ($delivered + $pending));
    }

    private function getCardsPending($distributor)
    {
        return $distributor->deliveries()
            ->whereIn('status', ['pending', 'in_progress'])
            ->sum('quantity');
    }

    private function getTotalPaid($distributor)
    {
        return $distributor->payments()->sum('amount');
    }

    private function getRemaining($distributor)
    {
        $totalDelivered = $this->getTotalDeliveredAmount($distributor);
        $totalPaid = $this->getTotalPaid($distributor);
        return max(0, $totalDelivered - $totalPaid);
    }

    private function getPaymentRate($distributor)
    {
        $totalDelivered = $this->getTotalDeliveredAmount($distributor);
        $totalPaid = $this->getTotalPaid($distributor);
        
        if ($totalDelivered > 0) {
            return round(($totalPaid / $totalDelivered) * 100, 2);
        }
        return 0;
    }

    private function getTotalDeliveredAmount($distributor)
    {
        return $distributor->deliveries()->sum('final_price');
    }

    private function getSchoolsServed($distributor)
    {
        return $distributor->deliveries()
            ->distinct('school_id')
            ->count('school_id');
    }

    /**
     * Statistiques par période
     */
    private function getDistributorPeriodStats($distributor, $period)
    {
        $deliveryQuery = $distributor->deliveries();
        $paymentQuery = $distributor->payments();

        switch ($period) {
            case 'day':
                $deliveryQuery->whereDate('delivery_date', Carbon::today());
                $paymentQuery->whereDate('payment_date', Carbon::today());
                break;
            case 'week':
                $deliveryQuery->whereBetween('delivery_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $paymentQuery->whereBetween('payment_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $deliveryQuery->whereMonth('delivery_date', Carbon::now()->month)
                    ->whereYear('delivery_date', Carbon::now()->year);
                $paymentQuery->whereMonth('payment_date', Carbon::now()->month)
                    ->whereYear('payment_date', Carbon::now()->year);
                break;
            case 'year':
                $deliveryQuery->whereYear('delivery_date', Carbon::now()->year);
                $paymentQuery->whereYear('payment_date', Carbon::now()->year);
                break;
        }

        return [
            'deliveries_count' => $deliveryQuery->count(),
            'deliveries_amount' => $deliveryQuery->sum('final_price'),
            'cards_delivered' => $deliveryQuery->sum('quantity'),
            'payments_count' => $paymentQuery->count(),
            'payments_amount' => $paymentQuery->sum('amount'),
            'new_schools' => $deliveryQuery->distinct('school_id')->count('school_id'),
        ];
    }

    /**
     * Livraisons récentes
     */
    private function getRecentDeliveries($distributor)
    {
        return $distributor->deliveries()
            ->with(['school:id,name,wilaya'])
            ->latest('delivery_date')
            ->limit(5)
            ->get()
            ->map(function ($delivery) {
                return [
                    'school' => $delivery->school->name,
                    'date' => Carbon::parse($delivery->delivery_date)->format('d/m/Y H:i'),
                    'quantity' => $delivery->quantity,
                    'amount' => $delivery->final_price,
                    'status' => $delivery->status,
                    'payment_status' => $delivery->payment_status,
                    'remaining_amount' => $delivery->remaining_amount,
                ];
            });
    }

    /**
     * Paiements récents
     */
    private function getRecentPayments($distributor)
    {
        return $distributor->payments()
            ->with(['delivery.school:id,name'])
            ->latest('payment_date')
            ->limit(5)
            ->get()
            ->map(function ($payment) {
                return [
                    'method' => $payment->method,
                    'date' => Carbon::parse($payment->payment_date)->format('d/m/Y H:i'),
                    'amount' => $payment->amount,
                    'school' => $payment->delivery->school->name ?? 'N/A',
                    'reference' => $payment->reference_number ?? 'Paiement',
                ];
            });
    }

    /**
     * Écoles récentes
     */
    private function getRecentSchools($distributor)
    {
        return School::whereHas('deliveries', function ($query) use ($distributor) {
                $query->where('distributor_id', $distributor->id);
            })
            ->latest()
            ->limit(5)
            ->get(['id', 'name', 'wilaya', 'commune', 'manager_name'])
            ->map(function ($school) use ($distributor) {
                $totalDeliveries = $school->deliveries()
                    ->where('distributor_id', $distributor->id)
                    ->count();
                $totalAmount = $school->deliveries()
                    ->where('distributor_id', $distributor->id)
                    ->sum('final_price');
                
                return [
                    'id' => $school->id,
                    'name' => $school->name,
                    'wilaya' => $school->wilaya,
                    'commune' => $school->commune,
                    'manager' => $school->manager_name,
                    'total_deliveries' => $totalDeliveries,
                    'total_amount' => $totalAmount,
                ];
            });
    }

    /**
     * Prévisions
     */
    private function getForecast($distributor)
    {
        // Moyenne des 3 derniers mois
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        
        $avgMonthlyDeliveries = $distributor->deliveries()
            ->where('delivery_date', '>=', $threeMonthsAgo)
            ->select(DB::raw('AVG(quantity) as avg_quantity'), DB::raw('AVG(final_price) as avg_amount'))
            ->first();

        $avgMonthlyPayments = $distributor->payments()
            ->where('payment_date', '>=', $threeMonthsAgo)
            ->select(DB::raw('AVG(amount) as avg_amount'))
            ->first();

        $remaining = $this->getRemaining($distributor);
        $paymentRate = $this->getPaymentRate($distributor);

        $forecast = [
            'estimated_monthly_deliveries' => round($avgMonthlyDeliveries->avg_quantity ?? 0),
            'estimated_monthly_revenue' => round($avgMonthlyDeliveries->avg_amount ?? 0, 2),
            'estimated_monthly_payments' => round($avgMonthlyPayments->avg_amount ?? 0, 2),
            'time_to_complete_payments' => $paymentRate > 0 ? round($remaining / ($avgMonthlyPayments->avg_amount ?? 1)) : 0,
            'payment_completion_date' => $paymentRate > 0 
                ? Carbon::now()->addMonths(round($remaining / ($avgMonthlyPayments->avg_amount ?? 1)))->format('d/m/Y')
                : null,
        ];

        return $forecast;
    }

    /**
     * Rapport détaillé pour distributeur
     */
    public function detailedReport(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'distributor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }

            $distributor = $user->distributorProfile;
            
            if (!$distributor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil distributeur non trouvé.'
                ], 404);
            }

            $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
            $schoolId = $request->get('school_id');

            $query = $distributor->deliveries();
            $paymentQuery = $distributor->payments();

            // Filtres
            $query->whereBetween('delivery_date', [$startDate, $endDate]);
            $paymentQuery->whereBetween('payment_date', [$startDate, $endDate]);

            if ($schoolId) {
                $query->where('school_id', $schoolId);
                $paymentQuery->where('school_id', $schoolId);
            }

            // Livraisons détaillées
            $deliveries = $query->with(['school:id,name,wilaya'])
                ->orderBy('delivery_date', 'desc')
                ->paginate($request->get('per_page', 50));

            // Paiements détaillés
            $payments = $paymentQuery->with(['delivery.school:id,name'])
                ->orderBy('payment_date', 'desc')
                ->paginate($request->get('per_page', 50));

            // Résumé
            $summary = [
                'total_deliveries' => $query->count(),
                'total_cards' => $query->sum('quantity'),
                'total_revenue' => $query->sum('final_price'),
                'total_payments' => $paymentQuery->count(),
                'total_collected' => $paymentQuery->sum('amount'),
                'balance' => $query->sum('final_price') - $paymentQuery->sum('amount'),
                'payment_rate' => $query->sum('final_price') > 0 
                    ? round(($paymentQuery->sum('amount') / $query->sum('final_price')) * 100, 2)
                    : 0,
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
                    'payments' => $payments,
                    'filters' => [
                        'school_id' => $schoolId,
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
     * Statistiques par école
     */
    public function schoolStats()
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'distributor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }

            $distributor = $user->distributorProfile;
            
            if (!$distributor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil distributeur non trouvé.'
                ], 404);
            }

            $schoolStats = School::whereHas('deliveries', function ($query) use ($distributor) {
                    $query->where('distributor_id', $distributor->id);
                })
                ->withCount(['deliveries' => function ($query) use ($distributor) {
                    $query->where('distributor_id', $distributor->id);
                }])
                ->withSum(['deliveries' => function ($query) use ($distributor) {
                    $query->where('distributor_id', $distributor->id);
                }], 'final_price')
                ->withSum(['deliveries' => function ($query) use ($distributor) {
                    $query->where('distributor_id', $distributor->id);
                }], 'quantity')
                ->orderByDesc('deliveries_sum_final_price')
                ->get()
                ->map(function ($school) use ($distributor) {
                    $totalPaid = Payment::whereHas('delivery', function ($query) use ($distributor, $school) {
                            $query->where('distributor_id', $distributor->id)
                                ->where('school_id', $school->id);
                        })
                        ->sum('amount');

                    return [
                        'id' => $school->id,
                        'name' => $school->name,
                        'wilaya' => $school->wilaya,
                        'commune' => $school->commune,
                        'manager' => $school->manager_name,
                        'total_deliveries' => $school->deliveries_count,
                        'total_cards' => $school->deliveries_sum_quantity ?? 0,
                        'total_amount' => $school->deliveries_sum_final_price ?? 0,
                        'total_paid' => $totalPaid,
                        'balance' => ($school->deliveries_sum_final_price ?? 0) - $totalPaid,
                        'payment_rate' => ($school->deliveries_sum_final_price ?? 0) > 0 
                            ? round(($totalPaid / $school->deliveries_sum_final_price) * 100, 2)
                            : 0,
                    ];
                });

            return response()->json([
                'success' => true,
                'school_stats' => $schoolStats,
                'total_schools' => $schoolStats->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de chargement des stats par école: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Performance du distributeur
     */
    public function performance(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'distributor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé.'
                ], 403);
            }

            $distributor = $user->distributorProfile;
            
            if (!$distributor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil distributeur non trouvé.'
                ], 404);
            }

            $period = $request->get('period', 'month');
            $months = $request->get('months', 6);

            $performanceData = [];
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();

                $monthDeliveries = $distributor->deliveries()
                    ->whereBetween('delivery_date', [$monthStart, $monthEnd])
                    ->get();

                $monthPayments = $distributor->payments()
                    ->whereBetween('payment_date', [$monthStart, $monthEnd])
                    ->get();

                $performanceData[] = [
                    'month' => $date->format('Y-m'),
                    'month_name' => $date->format('M Y'),
                    'deliveries_count' => $monthDeliveries->count(),
                    'deliveries_amount' => $monthDeliveries->sum('final_price'),
                    'cards_delivered' => $monthDeliveries->sum('quantity'),
                    'payments_count' => $monthPayments->count(),
                    'payments_amount' => $monthPayments->sum('amount'),
                    'new_schools' => $monthDeliveries->unique('school_id')->count(),
                    'payment_rate' => $monthDeliveries->sum('final_price') > 0 
                        ? round(($monthPayments->sum('amount') / $monthDeliveries->sum('final_price')) * 100, 2)
                        : 0,
                ];
            }

            // Calcul des moyennes et tendances
            $avgDeliveryAmount = collect($performanceData)->avg('deliveries_amount');
            $avgPaymentAmount = collect($performanceData)->avg('payments_amount');
            $avgPaymentRate = collect($performanceData)->avg('payment_rate');

            $trend = 'stable';
            if (count($performanceData) >= 2) {
                $lastMonth = $performanceData[count($performanceData) - 1]['deliveries_amount'];
                $previousMonth = $performanceData[count($performanceData) - 2]['deliveries_amount'];
                
                if ($previousMonth > 0) {
                    $growth = (($lastMonth - $previousMonth) / $previousMonth) * 100;
                    if ($growth > 10) $trend = 'growing';
                    elseif ($growth < -10) $trend = 'declining';
                }
            }

            return response()->json([
                'success' => true,
                'performance' => [
                    'monthly_data' => $performanceData,
                    'averages' => [
                        'delivery_amount' => round($avgDeliveryAmount, 2),
                        'payment_amount' => round($avgPaymentAmount, 2),
                        'payment_rate' => round($avgPaymentRate, 2),
                    ],
                    'trend' => $trend,
                    'summary' => [
                        'analysis_period' => "{$months} derniers mois",
                        'best_month' => collect($performanceData)->sortByDesc('deliveries_amount')->first(),
                        'most_active_schools' => $this->getMostActiveSchools($distributor, $months),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de chargement des performances: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Écoles les plus actives
     */
    private function getMostActiveSchools($distributor, $months)
    {
        $sinceDate = Carbon::now()->subMonths($months);
        
        return School::whereHas('deliveries', function ($query) use ($distributor, $sinceDate) {
                $query->where('distributor_id', $distributor->id)
                    ->where('delivery_date', '>=', $sinceDate);
            })
            ->withCount(['deliveries' => function ($query) use ($distributor, $sinceDate) {
                $query->where('distributor_id', $distributor->id)
                    ->where('delivery_date', '>=', $sinceDate);
            }])
            ->withSum(['deliveries' => function ($query) use ($distributor, $sinceDate) {
                $query->where('distributor_id', $distributor->id)
                    ->where('delivery_date', '>=', $sinceDate);
            }], 'final_price')
            ->orderByDesc('deliveries_sum_final_price')
            ->limit(5)
            ->get()
            ->map(function ($school) {
                return [
                    'name' => $school->name,
                    'deliveries_count' => $school->deliveries_count,
                    'total_amount' => $school->deliveries_sum_final_price ?? 0,
                    'wilaya' => $school->wilaya,
                ];
            });
    }
}