<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController2 extends Controller
{
    public function distributorDashboard(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'distributor') {
                return response()->json([
                    'success' => false,
                    'error' => 'Accès non autorisé.'
                ], 403);
            }
            
            $distributor = $user->distributorProfile;
            
            if (!$distributor) {
                return response()->json([
                    'success' => false,
                    'error' => 'Profil distributeur non trouvé.'
                ], 404);
            }
            
            // Statistiques de base
            $totalDeliveries = $distributor->deliveries()->count();
            $totalRevenue = $distributor->deliveries()->sum('final_price');
            $totalPaid = $distributor->payments()->sum('amount');
            $remainingAmount = $totalRevenue - $totalPaid;
            
            // Cartes basées sur les livraisons
            $totalCards = $distributor->deliveries()->sum('quantity');
            $cardsDelivered = $distributor->deliveries()->where('status', 'confirmed')->sum('quantity');
            $cardsPending = $distributor->deliveries()->whereIn('status', ['pending', 'in_progress'])->sum('quantity');
            $cardsAvailable = max(0, $totalCards - ($cardsDelivered + $cardsPending));
            
            // Livraisons récentes
            $recentDeliveries = $distributor->deliveries()
                ->with(['school:id,name'])
                ->orderBy('delivery_date', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($delivery) {
                    return [
                        'id' => $delivery->id,
                        'order_number' => 'CMD-' . str_pad($delivery->id, 6, '0', STR_PAD_LEFT),
                        'customer' => $delivery->school->name ?? 'N/A',
                        'school_name' => $delivery->school->name ?? 'N/A',
                        'status' => $delivery->status,
                        'amount' => (float) ($delivery->final_price ?? 0),
                        'quantity' => $delivery->quantity ?? 0,
                        'date' => $delivery->delivery_date ? Carbon::parse($delivery->delivery_date)->format('d/m/Y') : 'N/A',
                    ];
                });
            
            // Paiements récents
            $recentPayments = $distributor->payments()
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => (float) $payment->amount,
                        'payment_date' => $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d/m/Y H:i') : 'N/A',
                        'method' => $payment->method ?? 'cash',
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'totalOrders' => $totalDeliveries,
                    'totalRevenue' => (float) $totalRevenue,
                    'totalPaid' => (float) $totalPaid,
                    'remainingAmount' => (float) $remainingAmount,
                    
                    'totalCards' => $totalCards,
                    'cardsDelivered' => $cardsDelivered,
                    'cardsAvailable' => $cardsAvailable,
                    'cardsPending' => $cardsPending,
                    
                    'monthlyDeliveries' => $distributor->deliveries()
                        ->whereMonth('delivery_date', now()->month)
                        ->whereYear('delivery_date', now()->year)
                        ->count(),
                    'monthlyRevenue' => (float) $distributor->deliveries()
                        ->whereMonth('delivery_date', now()->month)
                        ->whereYear('delivery_date', now()->year)
                        ->sum('final_price'),
                    'assignedSchools' => $distributor->deliveries()
                        ->distinct('school_id')
                        ->count('school_id'),
                    
                    'totalPayments' => $distributor->payments()->count(),
                    'lastPaymentAmount' => $distributor->payments()->latest('payment_date')->first()->amount ?? 0,
                    'lastPaymentDate' => $distributor->payments()->latest('payment_date')->first()->payment_date ?? null,
                    'paymentRate' => $totalRevenue > 0 ? round(($totalPaid / $totalRevenue) * 100, 2) : 0,
                    
                    'recentOrders' => $recentDeliveries,
                    'recentPayments' => $recentPayments,
                    
                    'distributor' => [
                        'id' => $distributor->id,
                        'name' => $distributor->name ?? $user->name,
                        'email' => $user->email,
                        'phone' => $distributor->phone ?? $user->phone ?? 'Non renseigné',
                        'wilaya' => $distributor->wilaya ?? 'Non renseigné',
                    ],
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur de chargement du tableau de bord.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}