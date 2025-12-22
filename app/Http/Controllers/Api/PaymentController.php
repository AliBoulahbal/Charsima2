<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $delivery = Delivery::with('payments')->find($request->delivery_id);

        if (!$delivery) {
            return response()->json([
                'success' => false, 
                'message' => 'Livraison non trouvée'
            ], 404);
        }

        // CALCUL EXACT DU SOLDE RESTANT : somme des paiements existants
        $totalPaid = $delivery->payments->sum('amount');
        $remaining = $delivery->final_price - $totalPaid;
        
        // Debug logging
        \Log::info("Payment attempt for delivery {$delivery->id}", [
            'final_price' => $delivery->final_price,
            'total_paid' => $totalPaid,
            'remaining' => $remaining,
            'request_amount' => $request->amount,
            'delivery_paid_amount' => $delivery->paid_amount,
            'delivery_remaining_amount' => $delivery->remaining_amount,
        ]);

        if ($request->amount > $remaining) {
            return response()->json([
                'success' => false, 
                'message' => 'Le montant dépasse le solde restant ('.number_format($remaining, 2).' DZD)'
            ], 400);
        }

        // Création du paiement
        $payment = new Payment();
        $payment->delivery_id = $delivery->id;
        $payment->distributor_id = $delivery->distributor_id;
        $payment->amount = $request->amount;
        $payment->method = $request->payment_method;
        $payment->payment_date = $request->payment_date;
        $payment->note = $request->note ?? 'Paiement via application mobile';
        $payment->save();

        // Mise à jour des totaux dans la livraison
        $newTotalPaid = $totalPaid + $request->amount;
        $newRemaining = $delivery->final_price - $newTotalPaid;
        
        $delivery->paid_amount = $newTotalPaid;
        $delivery->remaining_amount = $newRemaining;
        
        // Si le solde est 0, on marque comme payé
        if ($newRemaining <= 0) {
            $delivery->payment_status = 'paid';
        } else {
            $delivery->payment_status = 'partial';
        }
        
        $delivery->save();

        // Recharger les relations
        $delivery->load('payments');

        return response()->json([
            'success' => true, 
            'message' => 'Paiement enregistré avec succès',
            'remaining' => $newRemaining,
            'total_paid' => $newTotalPaid,
            'payment' => $payment,
            'delivery' => [
                'id' => $delivery->id,
                'final_price' => $delivery->final_price,
                'paid_amount' => $delivery->paid_amount,
                'remaining_amount' => $delivery->remaining_amount,
                'payment_status' => $delivery->payment_status,
            ]
        ], 201);

    } catch (\Exception $e) {
        \Log::error('Payment error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);
        
        return response()->json([
            'success' => false, 
            'message' => 'Erreur serveur: ' . $e->getMessage()
        ], 500);
    }
}
    // Méthode pour récupérer les paiements
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Si c'est un distributeur, récupérer ses paiements
            if ($user->hasRole('distributor')) {
                $payments = Payment::where('distributor_id', $user->distributor->id)
                    ->with(['delivery.school'])
                    ->orderBy('payment_date', 'desc')
                    ->get();
            } else {
                // Pour les admins, tous les paiements
                $payments = Payment::with(['delivery.school', 'distributor'])
                    ->orderBy('payment_date', 'desc')
                    ->get();
            }
            
            return response()->json([
                'success' => true,
                'payments' => $payments
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}