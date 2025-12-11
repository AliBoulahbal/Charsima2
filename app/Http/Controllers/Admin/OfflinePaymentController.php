<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\School;
use App\Models\Kiosk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfflinePaymentController extends Controller
{
    /**
     * Liste des paiements en ligne en attente
     */
    public function pendingOnlinePayments(Request $request)
    {
        $query = Delivery::where('delivery_type', 'online')
            ->where('online_payment_status', '!=', 'payment_confirmed')
            ->with('school');
        
        // Filtres
        if ($request->has('status')) {
            $query->where('online_payment_status', $request->status);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->wilaya);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('payment_code', 'like', "%{$search}%")
                  ->orWhere('teacher_name', 'like', "%{$search}%")
                  ->orWhere('teacher_phone', 'like', "%{$search}%");
            });
        }
        
        $payments = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Statistiques
        $stats = [
            'total_pending' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_code_generated')
                ->count(),
            'total_expired' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_cancelled')
                ->count(),
            'total_confirmed_today' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_confirmed')
                ->whereDate('payment_confirmation_date', today())
                ->count(),
            'total_amount_pending' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_code_generated')
                ->sum('final_price'),
        ];
        
        $wilayas = Delivery::where('delivery_type', 'online')
            ->select('wilaya')
            ->distinct()
            ->orderBy('wilaya')
            ->pluck('wilaya');
        
        return view('admin.payments.online-pending', compact('payments', 'stats', 'wilayas'));
    }

    /**
     * Confirmer un paiement hors ligne (vue admin)
     */
    public function confirmPaymentView($paymentCode)
    {
        $sale = Delivery::with('school')
            ->where('payment_code', $paymentCode)
            ->firstOrFail();
        
        // Vérifier si déjà payé
        if ($sale->online_payment_status === 'payment_confirmed') {
            return redirect()->route('admin.payments.online-pending')
                ->with('warning', 'Ce paiement est déjà confirmé.');
        }
        
        // Vérifier si expiré
        $isExpired = $sale->payment_code_expires_at && now()->gt($sale->payment_code_expires_at);
        
        return view('admin.payments.confirm-online', compact('sale', 'isExpired'));
    }

    /**
     * Traiter la confirmation de paiement
     */
    public function processPaymentConfirmation(Request $request, $paymentCode)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,check,post_office',
            'payment_date' => 'required|date',
            'payment_receipt_number' => 'required|string|max:100',
            'bank_deposit_slip' => 'nullable|string|max:100',
            'payment_verification_notes' => 'nullable|string|max:500',
            'confirm_delivery' => 'nullable|boolean',
        ]);
        
        $sale = Delivery::where('payment_code', $paymentCode)->firstOrFail();
        
        // Vérifications
        if ($sale->online_payment_status === 'payment_confirmed') {
            return back()->with('error', 'Ce paiement est déjà confirmé.');
        }
        
        if ($sale->payment_code_expires_at && now()->gt($sale->payment_code_expires_at)) {
            return back()->with('error', 'Le code de paiement a expiré.');
        }
        
        // Mettre à jour la vente
        $sale->update([
            'online_payment_status' => 'payment_confirmed',
            'payment_method' => $request->payment_method,
            'payment_confirmation_date' => $request->payment_date,
            'payment_confirmed_by' => auth()->id(),
            'payment_receipt_number' => $request->payment_receipt_number,
            'bank_deposit_slip' => $request->bank_deposit_slip,
            'payment_verification_notes' => $request->payment_verification_notes,
            'status' => $request->confirm_delivery ? 'confirmed' : 'pending_delivery',
        ]);
        
        // Créer l'enregistrement de paiement
        Payment::create([
            'delivery_id' => $sale->id,
            'school_id' => $sale->school_id,
            'amount' => $sale->final_price,
            'payment_date' => $request->payment_date,
            'method' => $request->payment_method,
            'reference_number' => $request->payment_receipt_number,
            'wilaya' => $sale->school->wilaya ?? $sale->wilaya,
            'school_name' => $sale->school->name,
            'confirmed_by' => auth()->id(),
            'notes' => 'Paiement en ligne hors ligne confirmé. Code: ' . $paymentCode . 
                     ($request->bank_deposit_slip ? ' - Bordereau: ' . $request->bank_deposit_slip : ''),
        ]);
        
        return redirect()->route('admin.payments.online-pending')
            ->with('success', 'Paiement confirmé avec succès. Reçu: ' . $request->payment_receipt_number)
            ->with('receipt_number', $request->payment_receipt_number);
    }

    /**
     * Générer des rapports de paiements en ligne
     */
    public function onlinePaymentsReport(Request $request)
    {
        $query = Delivery::where('delivery_type', 'online')
            ->where('online_payment_status', 'payment_confirmed')
            ->with('school');
        
        // Filtres
        if ($request->has('date_from')) {
            $query->whereDate('payment_confirmation_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('payment_confirmation_date', '<=', $request->date_to);
        }
        
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->wilaya);
        }
        
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Statistiques par période
        $period = $request->period ?? 'monthly';
        
        switch ($period) {
            case 'daily':
                $stats = $this->getDailyStats($query);
                break;
            case 'weekly':
                $stats = $this->getWeeklyStats($query);
                break;
            case 'monthly':
                $stats = $this->getMonthlyStats($query);
                break;
            default:
                $stats = $this->getMonthlyStats($query);
        }
        
        // Paiements pour affichage
        $payments = $query->orderBy('payment_confirmation_date', 'desc')
            ->paginate(20);
        
        $wilayas = Delivery::where('delivery_type', 'online')
            ->select('wilaya')
            ->distinct()
            ->orderBy('wilaya')
            ->pluck('wilaya');
        
        $paymentMethods = [
            'cash' => 'Espèces',
            'bank_transfer' => 'Virement bancaire',
            'check' => 'Chèque',
            'post_office' => 'Poste',
        ];
        
        return view('admin.payments.online-report', compact(
            'payments', 'stats', 'wilayas', 'paymentMethods', 'period'
        ));
    }

    /**
     * Exporter les paiements en ligne
     */
    public function exportOnlinePayments(Request $request)
    {
        $query = Delivery::where('delivery_type', 'online')
            ->where('online_payment_status', 'payment_confirmed')
            ->with('school');
        
        // Appliquer les filtres
        if ($request->has('date_from')) {
            $query->whereDate('payment_confirmation_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('payment_confirmation_date', '<=', $request->date_to);
        }
        
        $payments = $query->orderBy('payment_confirmation_date', 'desc')->get();
        
        return view('admin.payments.export-online', compact('payments'));
    }

    /**
     * Tableau de bord des paiements en ligne
     */
    public function onlinePaymentsDashboard()
    {
        // Statistiques générales
        $stats = [
            'total_online_orders' => Delivery::where('delivery_type', 'online')->count(),
            'total_confirmed_payments' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_confirmed')
                ->count(),
            'total_pending_payments' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_code_generated')
                ->count(),
            'total_expired_payments' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_cancelled')
                ->count(),
            'total_revenue' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_confirmed')
                ->sum('final_price'),
            'pending_revenue' => Delivery::where('delivery_type', 'online')
                ->where('online_payment_status', 'payment_code_generated')
                ->sum('final_price'),
        ];
        
        // Paiements récents
        $recentPayments = Delivery::where('delivery_type', 'online')
            ->where('online_payment_status', 'payment_confirmed')
            ->with('school')
            ->orderBy('payment_confirmation_date', 'desc')
            ->limit(10)
            ->get();
        
        // Paiements en attente urgents (expirent dans 24h)
        $urgentPayments = Delivery::where('delivery_type', 'online')
            ->where('online_payment_status', 'payment_code_generated')
            ->where('payment_code_expires_at', '>', now())
            ->where('payment_code_expires_at', '<=', now()->addDay())
            ->with('school')
            ->orderBy('payment_code_expires_at')
            ->limit(10)
            ->get();
        
        // Statistiques par méthode de paiement
        $paymentMethodsStats = Delivery::where('delivery_type', 'online')
            ->where('online_payment_status', 'payment_confirmed')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(final_price) as amount')
            )
            ->groupBy('payment_method')
            ->get();
        
        // Statistiques par wilaya
        $wilayaStats = Delivery::where('delivery_type', 'online')
            ->where('online_payment_status', 'payment_confirmed')
            ->select(
                'wilaya',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(final_price) as amount')
            )
            ->whereNotNull('wilaya')
            ->groupBy('wilaya')
            ->orderByDesc('amount')
            ->limit(10)
            ->get();
        
        return view('admin.payments.online-dashboard', compact(
            'stats', 'recentPayments', 'urgentPayments', 
            'paymentMethodsStats', 'wilayaStats'
        ));
    }

    /**
     * Obtenir les statistiques quotidiennes
     */
    private function getDailyStats($query)
    {
        return $query->select(
                DB::raw('DATE(payment_confirmation_date) as date'),
                DB::raw('COUNT(*) as payments_count'),
                DB::raw('SUM(final_price) as total_amount')
            )
            ->whereNotNull('payment_confirmation_date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
    }
    
    /**
     * Obtenir les statistiques mensuelles
     */
    private function getMonthlyStats($query)
    {
        return $query->select(
                DB::raw('YEAR(payment_confirmation_date) as year'),
                DB::raw('MONTH(payment_confirmation_date) as month'),
                DB::raw('COUNT(*) as payments_count'),
                DB::raw('SUM(final_price) as total_amount')
            )
            ->whereNotNull('payment_confirmation_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
    }
}