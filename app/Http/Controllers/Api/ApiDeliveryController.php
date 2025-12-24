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
        
        // Autres filtres (Wilaya, Statut, Dates)
        if ($request->filled('wilaya')) $query->where('wilaya', $request->wilaya);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('delivery_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('delivery_date', '<=', $request->date_to);

        $deliveries = $query->with(['school:id,name,wilaya,commune', 'distributor:id,name', 'payments'])
            ->latest('delivery_date')
            ->get();

        // Transformer les données pour inclure les soldes calculés
        $transformedDeliveries = $deliveries->map(function ($delivery) {
            // Calculer le montant total payé
            $paidAmount = $delivery->payments->sum('amount');
            
            // Calculer le solde restant
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
                'payment_method' => $delivery->payment_method,
                'wilaya' => $delivery->wilaya,
                'latitude' => $delivery->latitude,
                'longitude' => $delivery->longitude,
                'location_validated' => $delivery->location_validated,
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
        return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
    }
}

    /**
     * Enregistrement d'une livraison (Fusion de la logique avec calcul auto)
     */
    public function storeWithLocation(Request $request)
    {
        try {
            $user = Auth::user();

            // 1. Calcul automatique du prix final si absent
            if (!$request->has('final_price') && $request->has(['quantity', 'unit_price'])) {
                $request->merge([
                    'final_price' => $request->quantity * $request->unit_price
                ]);
            }

            // 2. Validation
            $validator = Validator::make($request->all(), [
                'school_id'     => 'required|exists:schools,id',
                'quantity'      => 'required|integer|min:1',
                'unit_price'    => 'required|numeric|min:0',
                'final_price'   => 'required|numeric|min:0',
                'delivery_date' => 'required|date',
                'latitude'      => 'nullable|numeric',
                'longitude'     => 'nullable|numeric',
                'status'        => 'nullable|string|in:pending,completed,cancelled',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // 3. Identification du distributeur
            // On utilise le profil lié à l'utilisateur connecté
            $distributorId = $user->distributorProfile ? $user->distributorProfile->id : $request->distributor_id;

            if (!$distributorId) {
                return response()->json(['success' => false, 'message' => 'Profil distributeur introuvable'], 404);
            }

            // 4. Création
            $delivery = Delivery::create([
                'school_id'      => $request->school_id,
                'distributor_id' => $distributorId,
                'quantity'       => $request->quantity,
                'unit_price'     => $request->unit_price,
                'total_price'    => $request->final_price, // On remplit les deux pour la sécurité
                'final_price'    => $request->final_price,
                'delivery_date'  => $request->delivery_date,
                'latitude'       => $request->latitude,
                'longitude'      => $request->longitude,
                'status'         => $request->status ?? 'pending',
                'wilaya'         => School::find($request->school_id)->wilaya ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Livraison enregistrée avec succès',
                'delivery' => $delivery
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Détails d'une livraison
     */
    public function show($id)
    {
        $delivery = Delivery::with(['school', 'distributor'])->find($id);
        
        if (!$delivery) {
            return response()->json(['success' => false, 'message' => 'Livraison non trouvée'], 404);
        }

        return response()->json(['success' => true, 'delivery' => $delivery]);
    }

    /**
     * Mise à jour du statut
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $delivery = Delivery::find($id);
        if (!$delivery) return response()->json(['success' => false, 'message' => 'Introuvable'], 404);

        $delivery->update(['status' => $request->status]);

        return response()->json(['success' => true, 'delivery' => $delivery]);
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
                $distributorId = $user->distributorProfile ? $user->distributorProfile->id : null;
                if ($distributorId) $query->where('distributor_id', $distributorId);
            }

            $stats = [
                'total_deliveries'     => (int) $query->count(),
                'completed_deliveries' => (int) $query->where('status', 'completed')->count(),
                'total_amount'         => (float) $query->sum('final_price'),
                'total_cards'          => (int) $query->sum('quantity'),
                'recent_deliveries'    => $query->with('school:id,name')->latest()->limit(5)->get(),
            ];

            return response()->json(['success' => true, 'stats' => $stats]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}