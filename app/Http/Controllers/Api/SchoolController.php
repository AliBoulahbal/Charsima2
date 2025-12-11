// app/Http/Controllers/Api/SchoolController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * Liste des écoles
     */
    public function index(Request $request)
    {
        $query = School::query();
        
        // Filtres
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->wilaya);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%");
            });
        }
        
        $schools = $query->withCount('deliveries')
            ->addSelect([
                'total_delivered' => Delivery::selectRaw('COALESCE(SUM(total_price), 0)')
                    ->whereColumn('school_id', 'schools.id')
            ])
            ->orderBy('name')
            ->paginate($request->per_page ?? 20);
        
        return response()->json([
            'success' => true,
            'schools' => $schools,
        ]);
    }

    /**
     * Détails d'une école
     */
    public function show(School $school)
    {
        $school->loadCount('deliveries');
        
        // Statistiques de l'école
        $stats = [
            'total_deliveries' => $school->deliveries()->count(),
            'total_cards' => $school->deliveries()->sum('quantity'),
            'total_amount' => $school->deliveries()->sum('total_price'),
            'last_delivery' => $school->deliveries()->latest('delivery_date')->first(),
        ];
        
        return response()->json([
            'success' => true,
            'school' => $school,
            'stats' => $stats,
        ]);
    }

    /**
     * Trouver les écoles à proximité
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'nullable|numeric|min:0.1|max:10',
        ]);
        
        $radius = $request->radius_km ?? 2; // 2km par défaut
        $userLat = $request->latitude;
        $userLng = $request->longitude;
        
        $schools = School::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($school) use ($userLat, $userLng) {
                $distance = $school->calculateDistance($userLat, $userLng);
                $school->distance = $distance;
                $school->is_within_radius = $school->isWithinRadius($userLat, $userLng);
                return $school;
            })
            ->filter(function ($school) use ($radius) {
                return $school->distance <= $radius;
            })
            ->sortBy('distance')
            ->values();
        
        return response()->json([
            'success' => true,
            'schools' => $schools,
            'user_location' => [
                'latitude' => $userLat,
                'longitude' => $userLng,
            ],
            'search_radius_km' => $radius,
            'total_found' => $schools->count(),
        ]);
    }

    /**
     * Vérifier si l'utilisateur est à proximité d'une école
     */
    public function checkLocation(Request $request, School $school)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);
        
        $isWithinRadius = $school->isWithinRadius(
            $request->latitude,
            $request->longitude
        );
        
        $distance = $school->calculateDistance(
            $request->latitude,
            $request->longitude
        );
        
        return response()->json([
            'success' => true,
            'is_within_radius' => $isWithinRadius,
            'distance_km' => round($distance, 3),
            'school' => [
                'id' => $school->id,
                'name' => $school->name,
                'latitude' => $school->latitude,
                'longitude' => $school->longitude,
                'radius_km' => $school->radius,
            ],
            'user_location' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        ]);
    }

    /**
     * Créer une école (admin seulement)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'required|string|max:255',
            'student_count' => 'required|integer|min:0',
            'wilaya' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.01|max:1',
        ]);
        
        $school = School::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'École créée avec succès',
            'school' => $school,
        ], 201);
    }

    /**
     * Mettre à jour une école (admin seulement)
     */
    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'required|string|max:255',
            'student_count' => 'required|integer|min:0',
            'wilaya' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.01|max:1',
        ]);
        
        $school->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'École mise à jour avec succès',
            'school' => $school,
        ]);
    }

    /**
     * Supprimer une école (admin seulement)
     */
    public function destroy(School $school)
    {
        if ($school->deliveries()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer cette école car elle a des livraisons associées.',
            ], 400);
        }
        
        $school->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'École supprimée avec succès',
        ]);
    }

    /**
     * Obtenir la liste des wilayas
     */
    public function getWilayas()
    {
        $wilayas = School::select('wilaya')
            ->distinct()
            ->orderBy('wilaya')
            ->pluck('wilaya');
        
        return response()->json([
            'success' => true,
            'wilayas' => $wilayas,
        ]);
    }

    /**
     * Obtenir les livraisons d'une école
     */
    public function schoolDeliveries(School $school)
    {
        $deliveries = $school->deliveries()
            ->with(['distributor.user'])
            ->orderBy('delivery_date', 'desc')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'deliveries' => $deliveries,
        ]);
    }

    /**
     * Écoles pour un distributeur
     */
    public function distributorSchools(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'distributor') {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux distributeurs',
            ], 403);
        }
        
        // Écoles où le distributeur a déjà fait des livraisons
        $schools = School::whereHas('deliveries', function ($query) use ($user) {
            $query->where('distributor_id', $user->distributorProfile->id);
        })
        ->withCount(['deliveries' => function ($query) use ($user) {
            $query->where('distributor_id', $user->distributorProfile->id);
        }])
        ->orderBy('name')
        ->get();
        
        return response()->json([
            'success' => true,
            'schools' => $schools,
        ]);
    }
}