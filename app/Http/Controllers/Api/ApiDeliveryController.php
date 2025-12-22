<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ApiDeliveryController extends Controller
{
    /**
     * Display a listing of the deliveries for API.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $query = Delivery::query();
            
            // Si l'utilisateur est un distributeur, ne montrer que ses livraisons
            if ($user->role === 'distributor' && $user->distributor_id) {
                $query->where('distributor_id', $user->distributor_id);
            }
            
            // Filtrer par wilaya si spécifié
            if ($request->has('wilaya')) {
                $query->where('wilaya', $request->wilaya);
            }
            
            // Filtrer par statut
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            // Filtrer par date
            if ($request->has('date_from')) {
                $query->whereDate('delivery_date', '>=', $request->date_from);
            }
            
            if ($request->has('date_to')) {
                $query->whereDate('delivery_date', '<=', $request->date_to);
            }

            $deliveries = $query->with(['school:id,name,wilaya,commune', 'distributor:id,name'])
                ->latest('delivery_date')
                ->get();

            return response()->json([
                'success' => true,
                'deliveries' => $deliveries,
                'total' => $deliveries->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created delivery with location for API.
     */
    public function storeWithLocation(Request $request)
{
    $validated = $request->validate([
        'school_id' => 'required|exists:schools,id',
        'quantity' => 'required|integer|min:1',
        'unit_price' => 'required|numeric|min:0',
        'final_price' => 'required|numeric|min:0',
        'delivery_date' => 'required|date',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'status' => 'required|string',
        'distributor_id' => 'required|exists:users,id', // Important
    ]);

    $delivery = Delivery::create([
        'school_id' => $validated['school_id'],
        'distributor_id' => $validated['distributor_id'], // Assurez-vous que ce champ existe
        'quantity' => $validated['quantity'],
        'unit_price' => $validated['unit_price'],
        'final_price' => $validated['final_price'],
        'delivery_date' => $validated['delivery_date'],
        'latitude' => $validated['latitude'],
        'longitude' => $validated['longitude'],
        'status' => $validated['status'],
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Livraison enregistrée avec succès',
        'delivery' => $delivery
    ], 201);
}

    /**
     * Store a simple delivery (without location) for API.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'school_id' => 'required|exists:schools,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'delivery_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $school = School::find($request->school_id);
            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'École non trouvée'
                ], 404);
            }

            $totalPrice = $request->quantity * $request->unit_price;

            $delivery = Delivery::create([
                'school_id' => $request->school_id,
                'distributor_id' => $user->distributor_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'total_price' => $totalPrice,
                'final_price' => $totalPrice,
                'delivery_date' => $request->delivery_date,
                'status' => 'completed',
                'delivery_type' => 'school',
                'wilaya' => $school->wilaya,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livraison enregistrée avec succès',
                'delivery' => $delivery
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified delivery for API.
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $delivery = Delivery::with(['school', 'distributor'])->find($id);
            
            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Livraison non trouvée'
                ], 404);
            }

            // Vérifier les permissions
            if ($user->role === 'distributor' && $delivery->distributor_id !== $user->distributor_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'delivery' => $delivery
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update delivery status for API.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,completed,cancelled',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $delivery = Delivery::find($id);
            
            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Livraison non trouvée'
                ], 404);
            }

            // Vérifier les permissions
            if ($user->role === 'distributor' && $delivery->distributor_id !== $user->distributor_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $delivery->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'delivery' => $delivery
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery statistics for API dashboard.
     */
    public function getStats()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié'
                ], 401);
            }

            $query = Delivery::query();
            
            // Si l'utilisateur est un distributeur, filtrer ses livraisons
            if ($user->role === 'distributor' && $user->distributor_id) {
                $query->where('distributor_id', $user->distributor_id);
            }

            $totalDeliveries = $query->count();
            $completedDeliveries = $query->where('status', 'completed')->count();
            $pendingDeliveries = $query->where('status', 'pending')->count();
            $totalAmount = $query->sum('final_price');
            $totalCards = $query->sum('quantity');
            
            // Statistiques du mois en cours
            $currentMonth = now()->month;
            $currentYear = now()->year;
            
            $monthlyStats = $query->whereMonth('delivery_date', $currentMonth)
                ->whereYear('delivery_date', $currentYear)
                ->select(
                    DB::raw('COUNT(*) as deliveries_count'),
                    DB::raw('SUM(quantity) as cards_count'),
                    DB::raw('SUM(final_price) as amount')
                )
                ->first();

            // Dernières livraisons
            $recentDeliveries = $query->with('school:id,name')
                ->latest('delivery_date')
                ->limit(5)
                ->get(['id', 'school_id', 'delivery_date', 'quantity', 'final_price', 'status']);

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_deliveries' => $totalDeliveries,
                    'completed_deliveries' => $completedDeliveries,
                    'pending_deliveries' => $pendingDeliveries,
                    'total_amount' => (float) $totalAmount,
                    'total_cards' => $totalCards,
                    'monthly_deliveries' => $monthlyStats->deliveries_count ?? 0,
                    'monthly_cards' => $monthlyStats->cards_count ?? 0,
                    'monthly_amount' => (float) ($monthlyStats->amount ?? 0),
                    'recent_deliveries' => $recentDeliveries
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }
}