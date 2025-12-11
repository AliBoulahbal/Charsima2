// app/Http/Controllers/Api/DeliveryController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\School;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    /**
     * Liste des livraisons (filtrée par rôle)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Delivery::query();
        
        // Pour les distributeurs, seulement leurs livraisons
        if ($user->role === 'distributor' && $user->distributorProfile) {
            $query->where('distributor_id', $user->distributorProfile->id);
        }
        
        // Filtres
        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        
        if ($request->has('distributor_id')) {
            $query->where('distributor_id', $request->distributor_id);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->date_to);
        }
        
        if ($request->has('wilaya')) {
            $query->whereHas('school', function($q) use ($request) {
                $q->where('wilaya', $request->wilaya);
            });
        }
        
        $deliveries = $query->with(['school', 'distributor.user'])
            ->orderBy('delivery_date', 'desc')
            ->paginate($request->per_page ?? 20);
        
        return response()->json([
            'success' => true,
            'deliveries' => $deliveries,
        ]);
    }

    /**
     * Mes livraisons (pour distributeur)
     */
    public function myDeliveries(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'distributor' || !$user->distributorProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux distributeurs',
            ], 403);
        }
        
        $query = $user->distributorProfile->deliveries();
        
        // Filtres
        if ($request->has('month')) {
            $query->whereMonth('delivery_date', $request->month);
        }
        
        if ($request->has('year')) {
            $query->whereYear('delivery_date', $request->year);
        }
        
        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        
        $deliveries = $query->with('school')
            ->orderBy('delivery_date', 'desc')
            ->paginate($request->per_page ?? 20);
        
        return response()->json([
            'success' => true,
            'deliveries' => $deliveries,
        ]);
    }

    /**
     * Statistiques de mes livraisons
     */
    public function myDeliveriesStats(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'distributor' || !$user->distributorProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux distributeurs',
            ], 403);
        }
        
        $stats = [
            'total_deliveries' => $user->distributorProfile->deliveries()->count(),
            'total_cards' => $user->distributorProfile->deliveries()->sum('quantity'),
            'total_amount' => $user->distributorProfile->deliveries()->sum('total_price'),
            'monthly_deliveries' => $user->distributorProfile->deliveries()
                ->whereMonth('delivery_date', now()->month)
                ->whereYear('delivery_date', now()->year)
                ->count(),
            'monthly_amount' => $user->distributorProfile->deliveries()
                ->whereMonth('delivery_date', now()->month)
                ->whereYear('delivery_date', now()->year)
                ->sum('total_price'),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }

    /**
     * Créer une livraison
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'delivery_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
        ]);
        
        // Pour les distributeurs, utiliser leur propre ID
        $distributorId = $user->role === 'distributor' && $user->distributorProfile 
            ? $user->distributorProfile->id 
            : $request->distributor_id;
        
        if (!$distributorId) {
            return response()->json([
                'success' => false,
                'message' => 'Distributeur requis',
            ], 400);
        }
        
        // Calculer le prix total
        $totalPrice = $request->quantity * $request->unit_price;
        
        $delivery = Delivery::create([
            'school_id' => $request->school_id,
            'distributor_id' => $distributorId,
            'delivery_date' => $request->delivery_date,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $totalPrice,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Livraison créée avec succès',
            'delivery' => $delivery->load(['school', 'distributor.user']),
        ], 201);
    }

    /**
     * Créer une livraison avec validation GPS
     */
    public function storeWithLocation(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'delivery_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'force_create' => 'boolean',
        ]);
        
        $school = School::findOrFail($request->school_id);
        
        // Vérifier la géolocalisation
        $isWithinRadius = $school->isWithinRadius(
            $request->latitude,
            $request->longitude
        );
        
        if (!$isWithinRadius && !($request->force_create ?? false)) {
            $distance = $school->calculateDistance(
                $request->latitude,
                $request->longitude
            );
            
            return response()->json([
                'success' => false,
                'error' => 'LOCATION_OUT_OF_RANGE',
                'message' => 'Vous n\'êtes pas à proximité de cette école',
                'distance_km' => round($distance, 3),
                'allowed_radius_km' => $school->radius ?? 0.05,
                'school' => [
                    'name' => $school->name,
                    'latitude' => $school->latitude,
                    'longitude' => $school->longitude,
                ],
                'user_location' => [
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]
            ], 403);
        }
        
        // Pour les distributeurs, utiliser leur propre ID
        $distributorId = $user->role === 'distributor' && $user->distributorProfile 
            ? $user->distributorProfile->id 
            : $request->distributor_id;
        
        if (!$distributorId) {
            return response()->json([
                'success' => false,
                'message' => 'Distributeur requis',
            ], 400);
        }
        
        // Calculer le prix total
        $totalPrice = $request->quantity * $request->unit_price;
        
        $delivery = Delivery::create([
            'school_id' => $request->school_id,
            'distributor_id' => $distributorId,
            'delivery_date' => $request->delivery_date,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $totalPrice,
            'delivery_latitude' => $request->latitude,
            'delivery_longitude' => $request->longitude,
            'location_validated' => $isWithinRadius,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Livraison créée avec succès',
            'delivery' => $delivery->load(['school', 'distributor.user']),
            'location_validated' => $isWithinRadius,
            'distance_km' => $isWithinRadius ? 0 : round($distance, 3),
        ], 201);
    }

    /**
     * Détails d'une livraison
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['school', 'distributor.user']);
        
        return response()->json([
            'success' => true,
            'delivery' => $delivery,
        ]);
    }

    /**
     * Toutes les livraisons (admin seulement)
     */
    public function allDeliveries(Request $request)
    {
        $query = Delivery::query();
        
        // Filtres
        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }
        
        if ($request->has('distributor_id')) {
            $query->where('distributor_id', $request->distributor_id);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->date_to);
        }
        
        if ($request->has('wilaya')) {
            $query->whereHas('school', function($q) use ($request) {
                $q->where('wilaya', $request->wilaya);
            });
        }
        
        $deliveries = $query->with(['school', 'distributor.user'])
            ->orderBy('delivery_date', 'desc')
            ->paginate($request->per_page ?? 50);
        
        return response()->json([
            'success' => true,
            'deliveries' => $deliveries,
        ]);
    }

    /**
     * Statistiques des livraisons (admin seulement)
     */
    public function statistics(Request $request)
    {
        // Statistiques par mois
        $monthlyStats = Delivery::select(
                DB::raw('YEAR(delivery_date) as year'),
                DB::raw('MONTH(delivery_date) as month'),
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(quantity) as total_cards'),
                DB::raw('SUM(total_price) as total_amount')
            )
            ->whereNotNull('delivery_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
            
        // Statistiques par wilaya
        $wilayaStats = Delivery::join('schools', 'deliveries.school_id', '=', 'schools.id')
            ->select(
                'schools.wilaya',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.total_price) as total_amount')
            )
            ->groupBy('schools.wilaya')
            ->orderByDesc('total_amount')
            ->get();
            
        // Top écoles
        $topSchools = Delivery::join('schools', 'deliveries.school_id', '=', 'schools.id')
            ->select(
                'schools.id',
                'schools.name',
                'schools.wilaya',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.total_price) as total_amount')
            )
            ->groupBy('schools.id', 'schools.name', 'schools.wilaya')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
            
        // Top distributeurs
        $topDistributors = Delivery::join('distributors', 'deliveries.distributor_id', '=', 'distributors.id')
            ->join('users', 'distributors.user_id', '=', 'users.id')
            ->select(
                'distributors.id',
                'users.name',
                'distributors.wilaya',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.total_price) as total_amount')
            )
            ->groupBy('distributors.id', 'users.name', 'distributors.wilaya')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'monthly_stats' => $monthlyStats,
            'wilaya_stats' => $wilayaStats,
            'top_schools' => $topSchools,
            'top_distributors' => $topDistributors,
        ]);
    }
}