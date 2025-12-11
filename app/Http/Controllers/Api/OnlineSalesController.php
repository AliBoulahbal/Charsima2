<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\School;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OnlineSalesController extends Controller
{
    /**
     * API pour créer une vente en ligne (paiement hors ligne)
     */
    public function createOnlineSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_id' => 'required|exists:schools,id',
            'quantity' => 'required|integer|min:1|max:100',
            'unit_price' => 'required|integer|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_wilaya' => 'required|string|max:100',
            'customer_address' => 'required|string|max:500',
            'customer_cin' => 'nullable|string|max:20', // CIN du client
            'discount_code' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Calculer le discount si code fourni
        $discountPercentage = 0;
        if ($request->has('discount_code')) {
            $discountResult = $this->validateDiscountCode($request->discount_code);
            if ($discountResult['valid']) {
                $discountPercentage = $discountResult['percentage'];
            }
        }

        // Générer un ID de transaction unique
        $transactionId = 'ONL-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Calculer les prix
        $quantity = $request->quantity;
        $unitPrice = $request->unit_price;
        $totalPrice = $quantity * $unitPrice;
        $finalPrice = $totalPrice - ($totalPrice * ($discountPercentage / 100));

        // Créer la vente
        $sale = Delivery::create([
            'school_id' => $request->school_id,
            'delivery_type' => 'online',
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'discount_percentage' => $discountPercentage,
            'final_price' => $finalPrice,
            'delivery_date' => now(),
            'status' => 'pending_payment',
            'transaction_id' => $transactionId,
            'payment_method' => 'bank_transfer', // Méthode par défaut pour paiement hors ligne
            'teacher_name' => $request->customer_name,
            'teacher_phone' => $request->customer_phone,
            'wilaya' => $request->customer_wilaya,
            'notes' => $request->notes . "\nCIN: " . ($request->customer_cin ?? 'Non fourni'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commande créée avec succès. Veuillez effectuer le paiement.',
            'transaction_id' => $transactionId,
            'payment_code' => $sale->payment_code,
            'sale_summary' => [
                'commande_n°' => $transactionId,
                'code_paiement' => $sale->payment_code,
                'école' => $sale->school->name,
                'quantité' => $quantity . ' cartes',
                'prix_unitaire' => number_format($unitPrice, 0, ',', ' ') . ' DA',
                'prix_total' => number_format($totalPrice, 0, ',', ' ') . ' DA',
                'réduction' => $discountPercentage . '%',
                'montant_à_payer' => number_format($finalPrice, 0, ',', ' ') . ' DA',
                'date_limite_paiement' => $sale->payment_code_expires_at->format('d/m/Y à H:i'),
                'statut' => 'En attente de paiement',
            ],
            'payment_instructions' => $sale->payment_instructions,
        ], 201);
    }

    /**
     Vérifier le statut d'une commande
     */
    public function checkOrderStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required_without:payment_code|string',
            'payment_code' => 'required_without:transaction_id|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $query = Delivery::where('delivery_type', 'online');
        
        if ($request->has('transaction_id')) {
            $query->where('transaction_id', $request->transaction_id);
        }
        
        if ($request->has('payment_code')) {
            $query->where('payment_code', $request->payment_code);
        }
        
        $sale = $query->with('school')->first();
        
        if (!$sale) {
            return response()->json([
                'success' => false,
                'message' => 'Commande non trouvée',
            ], 404);
        }

        // Vérifier si le code de paiement a expiré
        $isExpired = $sale->payment_code_expires_at && now()->gt($sale->payment_code_expires_at);
        
        if ($isExpired && $sale->online_payment_status !== 'payment_confirmed') {
            $sale->update(['online_payment_status' => 'payment_cancelled']);
        }

        return response()->json([
            'success' => true,
            'order_status' => [
                'transaction_id' => $sale->transaction_id,
                'payment_code' => $sale->payment_code,
                'status' => $sale->status,
                'online_payment_status' => $sale->online_payment_status,
                'payment_code_status' => $sale->payment_code_status,
                'amount_due' => number_format($sale->final_price, 0, ',', ' ') . ' DA',
                'payment_expires_at' => $sale->payment_code_expires_at ? 
                    $sale->payment_code_expires_at->format('d/m/Y H:i') : null,
                'is_expired' => $isExpired,
                'days_remaining' => $sale->payment_code_expires_at ? 
                    max(0, now()->diffInDays($sale->payment_code_expires_at, false)) : null,
                'payment_confirmation_date' => $sale->payment_confirmation_date ?
                    $sale->payment_confirmation_date->format('d/m/Y H:i') : null,
                'payment_receipt_number' => $sale->payment_receipt_number,
            ],
            'order_details' => [
                'school' => $sale->school->name,
                'quantity' => $sale->quantity,
                'unit_price' => number_format($sale->unit_price, 0, ',', ' ') . ' DA',
                'total_price' => number_format($sale->total_price, 0, ',', ' ') . ' DA',
                'discount' => $sale->discount_percentage . '%',
                'final_price' => number_format($sale->final_price, 0, ',', ' ') . ' DA',
                'customer_name' => $sale->teacher_name,
                'customer_phone' => $sale->teacher_phone,
                'order_date' => $sale->created_at->format('d/m/Y H:i'),
            ],
            'payment_instructions' => $sale->payment_instructions,
        ]);
    }

    /**
     * API pour confirmer un paiement hors ligne (admin seulement)
     */
    public function confirmOfflinePayment(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'super_admin', 'cashier'])) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'payment_code' => 'required|string|exists:deliveries,payment_code',
            'payment_method' => 'required|in:cash,bank_transfer,check,post_office',
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:0',
            'payment_receipt_number' => 'required|string|max:100',
            'bank_deposit_slip' => 'nullable|string|max:100',
            'payment_verification_notes' => 'nullable|string|max:500',
            'confirm_delivery' => 'nullable|boolean', // Si on doit marquer la livraison comme confirmée
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $sale = Delivery::where('payment_code', $request->payment_code)->first();
        
        // Vérifier que la commande n'est pas déjà payée
        if ($sale->online_payment_status === 'payment_confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande est déjà payée',
            ], 400);
        }
        
        // Vérifier que le code n'a pas expiré
        if ($sale->payment_code_expires_at && now()->gt($sale->payment_code_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Le code de paiement a expiré',
            ], 400);
        }
        
        // Vérifier que le montant correspond
        if ($request->payment_amount != $sale->final_price) {
            return response()->json([
                'success' => false,
                'message' => 'Le montant payé ne correspond pas au montant dû',
                'due_amount' => $sale->final_price,
                'paid_amount' => $request->payment_amount,
            ], 400);
        }

        // Mettre à jour le statut de paiement
        $sale->update([
            'online_payment_status' => 'payment_confirmed',
            'payment_method' => $request->payment_method,
            'payment_confirmation_date' => $request->payment_date,
            'payment_confirmed_by' => $user->id,
            'payment_receipt_number' => $request->payment_receipt_number,
            'bank_deposit_slip' => $request->bank_deposit_slip,
            'payment_verification_notes' => $request->payment_verification_notes,
            'status' => $request->confirm_delivery ? 'confirmed' : 'pending_delivery',
        ]);

        // Enregistrer le paiement dans la table payments
        $payment = Payment::create([
            'delivery_id' => $sale->id,
            'school_id' => $sale->school_id,
            'amount' => $request->payment_amount,
            'payment_date' => $request->payment_date,
            'method' => $request->payment_method,
            'reference_number' => $request->payment_receipt_number,
            'wilaya' => $sale->school->wilaya ?? $sale->wilaya,
            'school_name' => $sale->school->name,
            'confirmed_by' => $user->id,
            'notes' => 'Paiement hors ligne - Code: ' . $sale->payment_code . 
                     ($request->bank_deposit_slip ? ' - Bordereau: ' . $request->bank_deposit_slip : '') .
                     ($request->payment_verification_notes ? "\n" . $request->payment_verification_notes : ''),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Paiement confirmé avec succès',
            'confirmation' => [
                'transaction_id' => $sale->transaction_id,
                'payment_code' => $sale->payment_code,
                'payment_receipt_number' => $request->payment_receipt_number,
                'confirmation_date' => now()->format('d/m/Y H:i'),
                'confirmed_by' => $user->name,
                'new_status' => $sale->status,
            ],
            'invoice' => [
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT),
                'client' => $sale->teacher_name,
                'amount' => number_format($request->payment_amount, 0, ',', ' ') . ' DA',
                'payment_method' => $this->getPaymentMethodLabel($request->payment_method),
            ],
        ]);
    }

    /**
     * API pour générer un nouveau code de paiement (renouvellement)
     */
    public function renewPaymentCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:deliveries,transaction_id',
            'customer_phone' => 'required|string|max:20', // Vérification de sécurité
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $sale = Delivery::where('transaction_id', $request->transaction_id)->first();
        
        // Vérifier que le client correspond
        if ($sale->teacher_phone !== $request->customer_phone) {
            return response()->json([
                'success' => false,
                'message' => 'Numéro de téléphone incorrect',
            ], 403);
        }
        
        // Vérifier que la commande n'est pas déjà payée
        if ($sale->online_payment_status === 'payment_confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande est déjà payée',
            ], 400);
        }
        
        // Vérifier que l'ancien code a expiré
        if (!$sale->payment_code_expires_at || now()->lt($sale->payment_code_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'L\'ancien code de paiement est encore valide',
                'expires_at' => $sale->payment_code_expires_at->format('d/m/Y H:i'),
            ], 400);
        }

        // Générer un nouveau code
        $newPaymentCode = 'PAY-' . strtoupper(Str::random(8));
        $newExpiry = now()->addDays(2); // Nouvelle expiration dans 2 jours
        
        $sale->update([
            'payment_code' => $newPaymentCode,
            'payment_code_expires_at' => $newExpiry,
            'online_payment_status' => 'payment_code_generated',
            'status' => 'pending_payment',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nouveau code de paiement généré',
            'new_payment_code' => $newPaymentCode,
            'new_expiry_date' => $newExpiry->format('d/m/Y à H:i'),
            'instructions' => $sale->payment_instructions,
        ]);
    }

    /**
     * API pour annuler une commande en attente
     */
    public function cancelPendingOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:deliveries,transaction_id',
            'customer_phone' => 'required|string|max:20',
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $sale = Delivery::where('transaction_id', $request->transaction_id)->first();
        
        // Vérifications
        if ($sale->teacher_phone !== $request->customer_phone) {
            return response()->json([
                'success' => false,
                'message' => 'Numéro de téléphone incorrect',
            ], 403);
        }
        
        if ($sale->online_payment_status === 'payment_confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'annuler une commande déjà payée',
            ], 400);
        }

        // Annuler la commande
        $sale->update([
            'status' => 'cancelled',
            'online_payment_status' => 'payment_cancelled',
            'notes' => $sale->notes . "\nANNULÉ: " . ($request->reason ?? 'Client a annulé') . 
                     ' - ' . now()->format('d/m/Y H:i'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commande annulée avec succès',
            'cancellation_reference' => 'CANC-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'cancelled_at' => now()->format('d/m/Y H:i'),
        ]);
    }

    /**
     * API pour télécharger une facture/proforma
     */
    public function downloadInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:deliveries,transaction_id',
            'type' => 'required|in:proforma,receipt,payment_slip',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $sale = Delivery::with('school')->where('transaction_id', $request->transaction_id)->first();
        
        // Générer le contenu du document selon le type
        switch ($request->type) {
            case 'proforma':
                $content = $this->generateProformaInvoice($sale);
                $filename = 'Proforma-' . $sale->transaction_id . '.pdf';
                break;
                
            case 'receipt':
                if ($sale->online_payment_status !== 'payment_confirmed') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Reçu disponible seulement après paiement',
                    ], 400);
                }
                $content = $this->generateReceipt($sale);
                $filename = 'Reçu-' . $sale->payment_receipt_number . '.pdf';
                break;
                
            case 'payment_slip':
                $content = $this->generatePaymentSlip($sale);
                $filename = 'Bordereau-' . $sale->payment_code . '.pdf';
                break;
        }

        // En production, utiliser une bibliothèque PDF comme Dompdf
        return response()->json([
            'success' => true,
            'document' => [
                'type' => $request->type,
                'filename' => $filename,
                'content' => base64_encode($content), // Encoder en base64 pour l'API
                'download_url' => url("/api/download/{$request->type}/{$sale->transaction_id}"),
            ],
            'order_info' => [
                'transaction_id' => $sale->transaction_id,
                'amount' => number_format($sale->final_price, 0, ',', ' ') . ' DA',
                'status' => $sale->status,
            ],
        ]);
    }

    /**
     * Valider un code de réduction
     */
    private function validateDiscountCode($code)
    {
        // Codes de réduction statiques (en production, stockez-les en base de données)
        $discountCodes = [
            'ECOLE2024' => ['percentage' => 10, 'valid_until' => '2024-12-31', 'max_uses' => 100, 'used' => 42],
            'ENSEIGNANT' => ['percentage' => 15, 'valid_until' => '2024-12-31', 'max_uses' => 500, 'used' => 187],
            'PREMIEREACHAT' => ['percentage' => 5, 'valid_until' => '2024-12-31', 'max_uses' => 1000, 'used' => 623],
            'FAMILLENOMBREUSE' => ['percentage' => 20, 'valid_until' => '2024-12-31', 'max_uses' => 200, 'used' => 89],
            'RENTREE' => ['percentage' => 12, 'valid_until' => '2024-10-31', 'max_uses' => 300, 'used' => 156],
        ];
        
        $code = strtoupper(trim($code));
        
        if (!array_key_exists($code, $discountCodes)) {
            return ['valid' => false, 'message' => 'Code invalide'];
        }
        
        $discount = $discountCodes[$code];
        
        // Vérifier la date d'expiration
        if (now()->gt(Carbon::parse($discount['valid_until']))) {
            return ['valid' => false, 'message' => 'Code expiré'];
        }
        
        // Vérifier les utilisations maximales
        if ($discount['used'] >= $discount['max_uses']) {
            return ['valid' => false, 'message' => 'Code épuisé'];
        }
        
        return [
            'valid' => true,
            'percentage' => $discount['percentage'],
            'code' => $code,
        ];
    }

    /**
     * Obtenir le libellé d'une méthode de paiement
     */
    private function getPaymentMethodLabel($method)
    {
        $labels = [
            'cash' => 'Espèces',
            'bank_transfer' => 'Virement bancaire',
            'check' => 'Chèque',
            'post_office' => 'Poste Algérienne',
            'card' => 'Carte bancaire',
        ];
        
        return $labels[$method] ?? $method;
    }

    /**
     * Générer une facture proforma (exemple simplifié)
     */
    private function generateProformaInvoice($sale)
    {
        // En production, utilisez Dompdf ou une bibliothèque similaire
        $html = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .info { margin: 20px 0; }
                .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
                .total { font-weight: bold; text-align: right; }
                .footer { margin-top: 50px; font-size: 12px; text-align: center; }
                .watermark { position: absolute; opacity: 0.1; font-size: 80px; transform: rotate(-45deg); }
            </style>
        </head>
        <body>
            <div class='watermark'>PROFORMA</div>
            <div class='header'>
                <h1>FACTURE PROFORMA</h1>
                <h3>Société des Cartes Scolaires</h3>
            </div>
            
            <div class='info'>
                <p><strong>N° Commande:</strong> {$sale->transaction_id}</p>
                <p><strong>Date:</strong> " . $sale->created_at->format('d/m/Y') . "</p>
                <p><strong>Code Paiement:</strong> {$sale->payment_code}</p>
                <p><strong>Validité:</strong> " . $sale->payment_code_expires_at->format('d/m/Y à H:i') . "</p>
            </div>
            
            <div class='info'>
                <h3>Client:</h3>
                <p>{$sale->teacher_name}</p>
                <p>Tél: {$sale->teacher_phone}</p>
                <p>Wilaya: {$sale->wilaya}</p>
            </div>
            
            <table class='table'>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>Carte scolaire - {$sale->school->name}</td>
                    <td>{$sale->quantity}</td>
                    <td>" . number_format($sale->unit_price, 0, ',', ' ') . " DA</td>
                    <td>" . number_format($sale->total_price, 0, ',', ' ') . " DA</td>
                </tr>
                <tr>
                    <td colspan='3'>Remise ({$sale->discount_percentage}%)</td>
                    <td>-" . number_format($sale->total_price - $sale->final_price, 0, ',', ' ') . " DA</td>
                </tr>
                <tr class='total'>
                    <td colspan='3'>TOTAL À PAYER</td>
                    <td>" . number_format($sale->final_price, 0, ',', ' ') . " DA</td>
                </tr>
            </table>
            
            <div class='info'>
                <h3>Instructions de paiement:</h3>
                <p>1. Présentez ce code au guichet: <strong>{$sale->payment_code}</strong></p>
                <p>2. Effectuez le paiement avant: <strong>" . $sale->payment_code_expires_at->format('d/m/Y à H:i') . "</strong></p>
                <p>3. Conservez ce document comme justificatif</p>
            </div>
            
            <div class='footer'>
                <p>Cet document est une facture proforma et ne constitue pas une facture définitive.</p>
                <p>La facture définitive sera émise après réception du paiement.</p>
            </div>
        </body>
        </html>
        ";
        
        return $html; // En production, convertissez en PDF
    }

    // ... autres méthodes generateReceipt et generatePaymentSlip similaires ...
}