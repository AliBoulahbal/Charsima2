<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\School;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        
        $deliveries = $query->with(['school', 'distributor', 'kiosk'])->orderBy('delivery_date', 'desc')->paginate(15);
        
        return response()->json([
            'success' => true,
            'deliveries' => $deliveries,
        ]);
    }

    /**
     * Créer une livraison SANS validation GPS (méthode store)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;

        if (!$distributor) {
            throw ValidationException::withMessages(['user' => 'Profil distributeur non valide.']);
        }
        
        // Validation minimale (sans coords)
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'quantity' => 'required|integer|min:1',
            // unit_price est la source de vérité pour le prix (saisie utilisateur)
            'unit_price' => 'required|numeric|min:0', 
            'discount_percentage' => 'nullable|numeric|between:0,100',
            'final_price' => 'nullable|numeric|min:0', // Recalculé par le serveur
            'delivery_date' => 'required|date',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);
        
        // --- LOGIQUE DE CALCUL PAR MULTIPLICATION CORRIGÉE ---
        $unitPrice = $validated['unit_price'];
        $quantity = $validated['quantity'];
        $discountPercentage = $validated['discount_percentage'] ?? 0.0;
        
        // 1. total_price (Prix avant escompte) = unit_price * quantity
        $validated['total_price'] = $unitPrice * $quantity;
        
        // 2. final_price (Prix TTC après escompte)
        $validated['final_price'] = $validated['total_price'] * (1 - ($discountPercentage / 100));
        $validated['final_price'] = round($validated['final_price'], 2); // Arrondir

        $validated['distributor_id'] = $distributor->id;
        $validated['location_validated'] = false; // Par défaut sans GPS

        $delivery = Delivery::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Livraison enregistrée avec succès (sans localisation).',
            'delivery' => $delivery,
        ], 201);
    }
    
    /**
     * Créer une livraison avec validation GPS (méthode storeWithLocation)
     */
    public function storeWithLocation(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;

        if (!$distributor) {
            throw ValidationException::withMessages(['user' => 'Profil distributeur non valide.']);
        }

        // 1. Validation des champs (inclut la localisation)
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'quantity' => 'required|integer|min:1',
            // unit_price est la source de vérité pour le prix
            'unit_price' => 'required|numeric|min:0', 
            'final_price' => 'nullable|numeric|min:0', // Recalculé par le serveur
            'discount_percentage' => 'nullable|numeric|between:0,100',
            'delivery_date' => 'required|date',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);
        
        // --- LOGIQUE DE CALCUL PAR MULTIPLICATION CORRIGÉE ---
        $unitPrice = $validated['unit_price'];
        $quantity = $validated['quantity'];
        $discountPercentage = $validated['discount_percentage'] ?? 0.0;
        
        // 2. total_price (Prix avant escompte) = unit_price * quantity
        // EX: 1000 * 1000 = 1,000,000
        $validated['total_price'] = $unitPrice * $quantity;

        // 3. final_price (Prix TTC après escompte)
        $validated['final_price'] = $validated['total_price'] * (1 - ($discountPercentage / 100));
        $validated['final_price'] = round($validated['final_price'], 2); // Arrondir
        
        // 4. Vérification de proximité
        $school = School::find($validated['school_id']);
        $validated['distributor_id'] = $distributor->id;
        $validated['location_validated'] = false; // Par défaut non validé
        
        if ($school && $school->latitude && $school->longitude) {
            // Assurez-vous que la méthode getDistanceTo existe sur le modèle School
            // Le calcul de distance est crucial pour la validation de proximité
            $distance = $school->getDistanceTo($validated['latitude'], $validated['longitude']);
            
            // Le rayon de validation est généralement 1 km (1000 mètres)
            if ($distance <= 1.0) { 
                $validated['location_validated'] = true;
            }
        }
        
        // 5. Création de la livraison
        $delivery = Delivery::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Livraison enregistrée avec succès.',
            'delivery' => $delivery,
        ], 201);
    }
    
    /**
     * Obtenir les statistiques du tableau de bord
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();
        
        // Ces méthodes supposent que vous avez des méthodes statiques dans votre modèle Delivery
        $monthlyStats = Delivery::getMonthlyStats(now()->year);
        $wilayaStats = Delivery::getWilayaStats();
        
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