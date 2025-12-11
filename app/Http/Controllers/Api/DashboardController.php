// app/Http/Controllers/Api/DashboardController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\School;
use App\Models\User;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Statistiques générales (selon le rôle)
     */
    public function stats(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return $this->adminStats($request);
        } elseif ($user->role === 'distributor') {
            return $this->distributorStats($request);
        }
        
        return response()->json(['success' => false, 'error' => 'Accès non autorisé'], 403);
    }
    
    /**
     * Statistiques pour admin
     */
    public function adminStats(Request $request)
    {
        // Statistiques globales
        $totalDeliveries = Delivery::count();
        $totalCards = Delivery::sum('quantity');
        $totalExpected = Delivery::sum('total_price');
        $totalPaid = Payment::sum('amount');
        $remaining = $totalExpected - $totalPaid;
        
        $distributorCount = User::where('role', 'distributor')->count();
        $schoolCount = School::count();
        
        // Statistiques du mois courant
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $monthlyDeliveries = Delivery::whereMonth('delivery_date', $currentMonth)
            ->whereYear('delivery_date', $currentYear)
            ->count();
        
        $monthlyAmount = Delivery::whereMonth('delivery_date', $currentMonth)
            ->whereYear('delivery_date', $currentYear)
            ->sum('total_price');
        
        // Dernières livraisons
        $recentDeliveries = Delivery::with(['school', 'distributor.user'])
            ->orderBy('delivery_date', 'desc')
            ->limit(10)
            ->get();
        
        // Écoles avec plus de livraisons
        $topSchools = School::withCount('deliveries')
            ->orderBy('deliveries_count', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total_deliveries' => $totalDeliveries,
                'total_cards' => $totalCards,
                'total_expected' => $totalExpected,
                'total_paid' => $totalPaid,
                'remaining' => $remaining,
                'distributor_count' => $distributorCount,
                'school_count' => $schoolCount,
                'monthly_deliveries' => $monthlyDeliveries,
                'monthly_amount' => $monthlyAmount,
            ],
            'recent_deliveries' => $recentDeliveries,
            'top_schools' => $topSchools,
        ]);
    }
    
    /**
     * Statistiques pour distributeur
     */
    public function distributorStats(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;
        
        if (!$distributor) {
            return response()->json(['success' => false, 'error' => 'Profil distributeur non trouvé'], 404);
        }
        
        // Statistiques du distributeur
        $totalDeliveries = $distributor->deliveries()->count();
        $totalDeliveredAmount = $distributor->deliveries()->sum('total_price');
        $totalPaid = $distributor->payments()->sum('amount');
        $remaining = $totalDeliveredAmount - $totalPaid;
        
        // Statistiques du mois courant
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $monthlyDeliveries = $distributor->deliveries()
            ->whereMonth('delivery_date', $currentMonth)
            ->whereYear('delivery_date', $currentYear)
            ->count();
        
        $monthlyAmount = $distributor->deliveries()
            ->whereMonth('delivery_date', $currentMonth)
            ->whereYear('delivery_date', $currentYear)
            ->sum('total_price');
        
        // Nombre d'écoles servies
        $schoolsServed = $distributor->deliveries()
            ->distinct('school_id')
            ->count('school_id');
        
        // Dernières livraisons
        $recentDeliveries = $distributor->deliveries()
            ->with('school')
            ->orderBy('delivery_date', 'desc')
            ->limit(10)
            ->get();
        
        // Derniers paiements
        $recentPayments = $distributor->payments()
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total_deliveries' => $totalDeliveries,
                'total_delivered_amount' => $totalDeliveredAmount,
                'total_paid' => $totalPaid,
                'remaining' => $remaining,
                'monthly_deliveries' => $monthlyDeliveries,
                'monthly_amount' => $monthlyAmount,
                'schools_served' => $schoolsServed,
            ],
            'recent_deliveries' => $recentDeliveries,
            'recent_payments' => $recentPayments,
        ]);
    }
}