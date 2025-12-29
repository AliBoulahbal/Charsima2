<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiDeliveryController extends Controller
{
    /**
     * Liste des livraisons (Filtrée par rôle)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
            }

            $query = Delivery::query();
            
            // Filtre automatique pour les distributeurs
            if ($user->role === 'distributor') {
                $distributorId = $user->distributorProfile ? $user->distributorProfile->id : null;
                if ($distributorId) {
                    $query->where('distributor_id', $distributorId);
                }
            }
            
            // Autres filtres
            if ($request->filled('wilaya')) $query->where('wilaya', $request->wilaya);
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('date_from')) $query->whereDate('delivery_date', '>=', $request->date_from);
            if ($request->filled('date_to')) $query->whereDate('delivery_date', '<=', $request->date_to);

            $deliveries = $query->with(['school:id,name,wilaya,commune', 'distributor:id,name', 'payments'])
                ->latest('delivery_date')
                ->get();

            // Transformer les données
            $transformedDeliveries = $deliveries->map(function ($delivery) {
                $paidAmount = $delivery->payments->sum('amount');
                $remainingAmount = max(0, $delivery->final_price - $paidAmount);
                
                return [
                    'id' => $delivery->id,
                    'school_id' => $delivery->school_id,
                    'distributor_id' => $delivery->distributor_id,
                    'delivery_date' => $delivery->delivery_date,
                    'quantity' => $delivery->quantity,
                    'unit_price' => $delivery->unit_price,
                    'total_price' => $delivery->total_price,
                    'final_price' => $delivery->final_price,
                    'paid_amount' => (float) $paidAmount,
                    'remaining_amount' => (float) $remainingAmount,
                    'payment_status' => $remainingAmount > 0 ? 'unpaid' : 'paid',
                    'status' => $delivery->status,
                    'transaction_id' => $delivery->transaction_id,
                    'wilaya' => $delivery->wilaya,
                    'latitude' => $delivery->latitude,
                    'longitude' => $delivery->longitude,
                    'school' => $delivery->school,
                    'distributor' => $delivery->distributor,
                    'created_at' => $delivery->created_at,
                    'updated_at' => $delivery->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'deliveries' => $transformedDeliveries,
                'total' => $deliveries->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur index deliveries: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Enregistrement d'une livraison (Nouvelle version corrigée)
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Utilisateur non authentifié'], 401);
            }

            // 1. Validation des données
            $validator = Validator::make($request->all(), [
                'school_id' => 'required|exists:schools,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'delivery_date' => 'required|date',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors()
                ], 422);
            }

            // 2. Récupérer le distributeur
            $distributor = $user->distributorProfile;
            if (!$distributor) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Profil distributeur introuvable'
                ], 404);
            }

            // 3. Récupérer l'école pour obtenir la wilaya
            $school = School::find($request->school_id);
            if (!$school) {
                return response()->json([
                    'success' => false, 
                    'message' => 'École introuvable'
                ], 404);
            }

            // 4. Calculer les prix
            $quantity = (int) $request->quantity;
            $unitPrice = (float) $request->unit_price;
            $totalPrice = $quantity * $unitPrice;
            $finalPrice = $totalPrice; // Dans votre cas, total_price et final_price sont identiques

            // 5. Créer la livraison
            $delivery = Delivery::create([
                'school_id' => $request->school_id,
                'distributor_id' => $distributor->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'final_price' => $finalPrice,
                'delivery_date' => $request->delivery_date,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => 'pending', // Statut par défaut
                'wilaya' => $school->wilaya, // Récupérer la wilaya de l'école
                'transaction_id' => 'DEL' . time() . rand(1000, 9999), // Générer un ID de transaction unique
            ]);

            // 6. Réponse
            return response()->json([
                'success' => true,
                'message' => 'Livraison enregistrée avec succès',
                'delivery' => $delivery
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur création livraison: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ancienne méthode storeWithLocation - Conserver pour compatibilité
     */
    public function storeWithLocation(Request $request)
    {
        return $this->store($request);
    }

    /**
     * Détails d'une livraison
     */
    public function show($id)
    {
        try {
            $delivery = Delivery::with(['school', 'distributor'])->find($id);
            
            if (!$delivery) {
                return response()->json(['success' => false, 'message' => 'Livraison non trouvée'], 404);
            }

            return response()->json(['success' => true, 'delivery' => $delivery]);
        } catch (\Exception $e) {
            Log::error('Erreur show delivery: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Mise à jour du statut
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|string|in:pending,completed,cancelled',
            ]);

            $delivery = Delivery::find($id);
            if (!$delivery) {
                return response()->json(['success' => false, 'message' => 'Livraison introuvable'], 404);
            }

            $delivery->update(['status' => $request->status]);

            return response()->json(['success' => true, 'delivery' => $delivery]);
        } catch (\Exception $e) {
            Log::error('Erreur updateStatus: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur serveur'], 500);
        }
    }

    /**
     * Statistiques globales et mensuelles
     */
    public function getStats()
    {
        try {
            $user = Auth::user();
            $query = Delivery::query();
            
            if ($user->role === 'distributor') {
                if ($user->distributorProfile) {
                    $distributorId = $user->distributorProfile->id;
                    $query->where('distributor_id', $distributorId);
                } else {
                    return response()->json([
                        'success' => true,
                        'stats' => [
                            'total_deliveries' => 0,
                            'completed_deliveries' => 0,
                            'total_amount' => 0,
                            'total_cards' => 0,
                            'recent_deliveries' => []
                        ]
                    ]);
                }
            }

            $stats = [
                'total_deliveries' => (int) $query->count(),
                'completed_deliveries' => (int) $query->where('status', 'completed')->count(),
                'total_amount' => (float) $query->sum('final_price'),
                'total_cards' => (int) $query->sum('quantity'),
            ];

            $recentDeliveries = $query->with('school:id,name')
                ->latest('delivery_date')
                ->limit(5)
                ->get()
                ->map(function ($delivery) {
                    return [
                        'id' => $delivery->id,
                        'school_name' => $delivery->school->name ?? 'École inconnue',
                        'quantity' => $delivery->quantity,
                        'amount' => $delivery->final_price,
                        'date' => $delivery->delivery_date,
                        'status' => $delivery->status,
                    ];
                });

            $stats['recent_deliveries'] = $recentDeliveries;

            return response()->json([
                'success' => true, 
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur getStats: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Erreur interne: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Données brutes pour formulaire
     */
    public function raw()
    {
        try {
            $user = Auth::user();
            
            $data = [
                'schools' => School::select('id', 'name', 'wilaya', 'commune')->get(),
            ];
            
            if ($user->isDistributor() && $user->distributorProfile) {
                $wilaya = $user->distributorProfile->wilaya;
                $data['schools'] = School::where('wilaya', $wilaya)
                    ->select('id', 'name', 'wilaya', 'commune')
                    ->get();
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur raw deliveries: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}