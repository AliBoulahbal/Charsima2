<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Kiosk;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KioskSalesController extends Controller
{
    /**
     * Créer une vente depuis un kiosque
     */
    public function createKioskSale(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est associé à un kiosque
        $kiosk = $user->kiosk ?? Kiosk::where('user_id', $user->id)->first();
        
        if (!$kiosk) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non associé à un kiosque',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'school_id' => 'required|exists:schools,id',
            'quantity' => 'required|integer|min:1|max:100',
            'unit_price' => 'required|integer|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'discount_percentage' => 'nullable|numeric|between:0,30',
            'payment_method' => 'required|in:cash,card,check',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Générer un ID de transaction
        $transactionId = 'KIO-' . strtoupper(Str::random(6)) . '-' . time();

        // Créer la vente
        $sale = Delivery::create([
            'school_id' => $request->school_id,
            'kiosk_id' => $kiosk->id,
            'delivery_type' => 'kiosk',
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'discount_percentage' => $request->discount_percentage ?? 0,
            'delivery_date' => now(),
            'status' => 'confirmed',
            'transaction_id' => $transactionId,
            'payment_method' => $request->payment_method,
            'teacher_name' => $request->customer_name,
            'teacher_phone' => $request->customer_phone,
            'notes' => $request->notes,
        ]);

        // Enregistrer automatiquement le paiement
        $payment = \App\Models\Payment::create([
            'kiosk_id' => $kiosk->id,
            'delivery_id' => $sale->id,
            'school_id' => $sale->school_id,
            'amount' => $sale->final_price,
            'payment_date' => now(),
            'method' => $request->payment_method,
            'wilaya' => $kiosk->wilaya,
            'school_name' => $sale->school->name,
            'reference_number' => 'KIOK-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
            'notes' => 'Vente kiosque - ' . $kiosk->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vente enregistrée avec succès',
            'transaction_id' => $transactionId,
            'sale' => $sale->load('school'),
            'payment' => $payment,
            'receipt_number' => 'REC-' . str_pad($sale->id, 8, '0', STR_PAD_LEFT),
        ], 201);
    }

    /**
     * Obtenir les ventes du kiosque
     */
    public function getKioskSales(Request $request)
    {
        $user = Auth::user();
        $kiosk = $user->kiosk ?? Kiosk::where('user_id', $user->id)->first();
        
        if (!$kiosk) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non associé à un kiosque',
            ], 403);
        }

        $query = $kiosk->deliveries()->with('school');

        // Filtres
        if ($request->has('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->date_to);
        }
        
        if ($request->has('type')) {
            $query->where('delivery_type', $request->type);
        }

        $sales = $query->orderBy('delivery_date', 'desc')
            ->paginate($request->per_page ?? 20);

        // Statistiques du jour
        $todayStats = [
            'sales_count' => $kiosk->deliveries()
                ->whereDate('delivery_date', today())
                ->count(),
            'total_amount' => $kiosk->deliveries()
                ->whereDate('delivery_date', today())
                ->sum('final_price'),
            'cash_sales' => $kiosk->deliveries()
                ->whereDate('delivery_date', today())
                ->where('payment_method', 'cash')
                ->sum('final_price'),
            'card_sales' => $kiosk->deliveries()
                ->whereDate('delivery_date', today())
                ->where('payment_method', 'card')
                ->sum('final_price'),
        ];

        return response()->json([
            'success' => true,
            'kiosk' => $kiosk,
            'sales' => $sales,
            'today_stats' => $todayStats,
        ]);
    }

    /**
     * Statistiques du kiosque
     */
    public function kioskStats(Request $request)
    {
        $user = Auth::user();
        $kiosk = $user->kiosk ?? Kiosk::where('user_id', $user->id)->first();
        
        if (!$kiosk) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non associé à un kiosque',
            ], 403);
        }

        $period = $request->period ?? 'month'; // day, week, month, year

        $stats = $this->calculateKioskStats($kiosk, $period);

        // Top produits/écoles
        $topSchools = $kiosk->deliveries()
            ->join('schools', 'deliveries.school_id', '=', 'schools.id')
            ->select(
                'schools.id',
                'schools.name',
                'schools.wilaya',
                \DB::raw('COUNT(*) as sales_count'),
                \DB::raw('SUM(final_price) as total_amount')
            )
            ->groupBy('schools.id', 'schools.name', 'schools.wilaya')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'kiosk' => $kiosk,
            'period' => $period,
            'stats' => $stats,
            'top_schools' => $topSchools,
        ]);
    }

    /**
     * Calculer les statistiques du kiosque
     */
    private function calculateKioskStats(Kiosk $kiosk, $period)
    {
        $query = $kiosk->deliveries();
        
        // Filtrer par période
        switch ($period) {
            case 'day':
                $query->whereDate('delivery_date', today());
                break;
            case 'week':
                $query->whereBetween('delivery_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('delivery_date', now()->month)
                      ->whereYear('delivery_date', now()->year);
                break;
            case 'year':
                $query->whereYear('delivery_date', now()->year);
                break;
        }

        return [
            'sales_count' => $query->count(),
            'total_revenue' => $query->sum('final_price'),
            'average_sale' => $query->avg('final_price'),
            'discount_given' => $query->sum(\DB::raw('total_price - final_price')),
            'by_payment_method' => $query->select(
                'payment_method',
                \DB::raw('COUNT(*) as count'),
                \DB::raw('SUM(final_price) as amount')
            )->groupBy('payment_method')->get(),
            'by_type' => $query->select(
                'delivery_type',
                \DB::raw('COUNT(*) as count'),
                \DB::raw('SUM(final_price) as amount')
            )->groupBy('delivery_type')->get(),
        ];
    }
}