<?php

// app/Http/Controllers/Api/MobileController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\School;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MobileController extends Controller
{
    // Dashboard stats pour distributeur
    public function distributorStats()
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;
        
        if (!$distributor) {
            return response()->json(['error' => 'Distributeur non trouvé'], 404);
        }
        
        $stats = [
            'total_deliveries' => $distributor->deliveries()->count(),
            'total_delivered_amount' => $distributor->deliveries()->sum('total_price'),
            'total_paid' => $distributor->payments()->sum('amount'),
            'remaining' => $distributor->deliveries()->sum('total_price') - $distributor->payments()->sum('amount'),
            'monthly_deliveries' => $distributor->deliveries()
                ->whereMonth('delivery_date', now()->month)
                ->sum('total_price'),
            'schools_served' => $distributor->deliveries()
                ->distinct('school_id')
                ->count('school_id'),
        ];
        
        return response()->json($stats);
    }
    
    // Mes livraisons (pour distributeur)
    public function myDeliveries(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;
        
        $query = $distributor->deliveries()->with('school');
        
        // Filtres
        if ($request->has('month')) {
            $query->whereMonth('delivery_date', $request->month);
        }
        
        if ($request->has('year')) {
            $query->whereYear('delivery_date', $request->year);
        }
        
        $deliveries = $query->orderBy('delivery_date', 'desc')
            ->paginate(15);
        
        return response()->json($deliveries);
    }
    
    // Mes paiements (pour distributeur)
    public function myPayments(Request $request)
    {
        $user = Auth::user();
        $distributor = $user->distributorProfile;
        
        $payments = $distributor->payments()
            ->orderBy('payment_date', 'desc')
            ->paginate(15);
        
        return response()->json($payments);
    }
}