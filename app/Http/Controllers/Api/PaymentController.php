<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validator = Validator::make($request->all(), [
                'delivery_id' => 'required|exists:deliveries,id',
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|string',
                'payment_date' => 'required|date',
                'note' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Charger la livraison avec ses paiements existants pour calculer le solde
            $delivery = Delivery::with('payments')->find($request->delivery_id);

            // CALCUL DU SOLDE
            $totalPaid = $delivery->payments->sum('amount');
            $remainingBefore = $delivery->final_price - $totalPaid;

            if ($request->amount > $remainingBefore) {
                return response()->json([
                    'success' => false, 
                    'message' => "Le montant dépasse le solde restant ($remainingBefore DA)"
                ], 422);
            }

            // ENREGISTREMENT
            $payment = Payment::create([
                'delivery_id' => $request->delivery_id,
                'distributor_id' => $user->distributorProfile->id, // Correction ici : utilise la relation du modèle User
                'amount' => $request->amount,
                'method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'notes' => $request->note,
                'school_id' => $delivery->school_id,
                'wilaya' => $delivery->wilaya,
            ]);

            // Mise à jour du statut de la livraison si payée totalement
            $newTotalPaid = $totalPaid + $request->amount;
            if ($newTotalPaid >= $delivery->final_price) {
                $delivery->update(['status' => 'completed']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès',
                'payment' => $payment
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur Paiement: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        try {
            $user = Auth::user();
            $query = Payment::with(['delivery.school']);

            if ($user->role === 'distributor') {
                $query->where('distributor_id', $user->distributorProfile->id);
            }

            $payments = $query->orderBy('payment_date', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'payments' => $payments
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}