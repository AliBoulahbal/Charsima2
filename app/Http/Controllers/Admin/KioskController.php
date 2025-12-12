<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kiosk;
use App\Models\User;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KioskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kiosk::query();
        
        // Recherche
        if ($request->filled('search')) { // Utilisation de filled() pour la robustesse
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                // CORRECTION: Suppression des backslashes de fin de chaîne
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('wilaya', 'like', "%{$search}%");
            });
        }
        
        // Filtre par wilaya
        if ($request->filled('wilaya')) { // Utilisation de filled()
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        // Filtre par statut
        if ($request->filled('status')) { // Utilisation de filled()
            $query->where('is_active', $request->input('status') === 'active');
        }
        
        $kiosks = $query->select([
                'kiosks.*',
                DB::raw('(SELECT COUNT(*) FROM deliveries WHERE deliveries.kiosk_id = kiosks.id) as sales_count'),
                DB::raw('(SELECT COALESCE(SUM(final_price), 0) FROM deliveries WHERE deliveries.kiosk_id = kiosks.id) as total_sales')
            ])
            ->orderBy('name')
            ->paginate(20);
        
        // Liste des wilayas
        $wilayas = Kiosk::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        
        return view('admin.kiosks.index', compact('kiosks', 'wilayas'));
    } // <-- Fin de la méthode index()

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wilayas = $this->getWilayas();
        $users = User::where('role', 'kiosk')
            ->orWhere('role', 'distributor')
            ->whereDoesntHave('kiosk')
            ->get();
        
        return view('admin.kiosks.create', compact('wilayas', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'wilaya' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Kiosk::create($validated);

        return redirect()->route('admin.kiosks.index')
            ->with('success', 'Kiosque créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kiosk $kiosk)
    {
        $kiosk->load(['deliveries.school', 'user']);
        
        // Statistiques
        $stats = [
            'total_sales' => $kiosk->deliveries()->sum('final_price'),
            'sales_count' => $kiosk->deliveries()->count(),
            'monthly_sales' => $kiosk->deliveries()
                ->whereMonth('delivery_date', now()->month)
                ->sum('final_price'),
            'average_sale' => $kiosk->deliveries()->avg('final_price'),
        ];
        
        // Ventes récentes
        $recentSales = $kiosk->deliveries()
            ->with('school')
            ->latest('delivery_date')
            ->limit(10)
            ->get();

        return view('admin.kiosks.show', compact('kiosk', 'stats', 'recentSales'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kiosk $kiosk)
    {
        $wilayas = $this->getWilayas();
        $users = User::where('role', 'kiosk')
            ->orWhere('role', 'distributor')
            ->whereDoesntHave('kiosk')
            ->orWhere('id', $kiosk->user_id)
            ->get();
        
        return view('admin.kiosks.edit', compact('kiosk', 'wilayas', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kiosk $kiosk)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'wilaya' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $kiosk->update($validated);

        return redirect()->route('admin.kiosks.index')
            ->with('success', 'Kiosque mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kiosk $kiosk)
    {
        if ($kiosk->deliveries()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce kiosque car il a des ventes associées.');
        }
        
        $kiosk->delete();

        return redirect()->route('admin.kiosks.index')
            ->with('success', 'Kiosque supprimé avec succès.');
    }

    /**
     * Ventes du kiosque
     */
    public function sales(Kiosk $kiosk, Request $request)
    {
        $query = $kiosk->deliveries()->with('school');
        
        // Filtres
        if ($request->filled('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->input('date_to'));
        }
        
        if ($request->filled('type')) {
            $query->where('delivery_type', $request->input('type'));
        }
        
        $sales = $query->latest('delivery_date')->paginate(20);
        
        return view('admin.kiosks.sales', compact('kiosk', 'sales'));
    }

    /**
     * Rapport financier
     */
    public function financialReport(Kiosk $kiosk)
    {
        $monthlySales = $kiosk->deliveries()
            ->select(
                DB::raw('YEAR(delivery_date) as year'),
                DB::raw('MONTH(delivery_date) as month'),
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(final_price) as total_amount'),
                DB::raw('AVG(discount_percentage) as avg_discount')
            )
            ->whereNotNull('delivery_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
            
        // Ventilation par type
        $typeStats = $kiosk->deliveries()
            ->select(
                'delivery_type',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(final_price) as total_amount')
            )
            ->groupBy('delivery_type')
            ->get();

        return view('admin.kiosks.financial-report', compact('kiosk', 'monthlySales', 'typeStats'));
    }

    /**
     * Liste des wilayas
     */
    private function getWilayas()
    {
        return [
            'Adrar', 'Chlef', 'Laghouat', 'Oum El Bouaghi', 'Batna', 'Béjaïa', 'Biskra', 'Béchar', 'Blida', 'Bouira',
            'Tamanrasset', 'Tébessa', 'Tlemcen', 'Tiaret', 'Tizi Ouzou', 'Alger', 'Djelfa', 'Jijel', 'Sétif', 'Saïda',
            'Skikda', 'Sidi Bel Abbès', 'Annaba', 'Guelma', 'Constantine', 'Médéa', 'Mostaganem', 'M\'Sila', 'Mascara',
            'Ouargla', 'Oran', 'El Bayadh', 'Illizi', 'Bordj Bou Arréridj', 'Boumerdès', 'El Tarf', 'Tindouf', 'Tissemsilt',
            'El Oued', 'Khenchela', 'Souk Ahras', 'Tipaza', 'Mila', 'Aïn Defla', 'Naâma', 'Aïn Témouchent', 'Ghardaïa',
            'Relizane', 'Timimoun', 'Bordj Badji Mokhtar', 'Ouled Djellal', 'Béni Abbès', 'In Salah', 'In Guezzam',
            'Touggourt', 'Djanet', 'El M\'Ghair', 'El Meniaa'
        ];
    }
}