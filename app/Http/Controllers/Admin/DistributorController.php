<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\User;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Distributor::with(['user', 'deliveries', 'payments']);
        
        // Recherche
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('wilaya', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filtre par wilaya
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        $distributors = $query->select([
                'distributors.*',
                DB::raw('(SELECT COUNT(*) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as deliveries_count'),
                DB::raw('(SELECT COALESCE(SUM(total_price), 0) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as total_delivered'),
                DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.distributor_id = distributors.id) as total_paid')
            ])
            ->orderByDesc('deliveries_count')
            ->paginate(20);
        
        // Liste des wilayas uniques
        $wilayas = Distributor::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        
        return view('admin.distributors.index', compact('distributors', 'wilayas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wilayas = $this->getWilayas();
        $users = User::where('role', 'distributor')
            ->whereDoesntHave('distributorProfile')
            ->get();
        
        return view('admin.distributors.create', compact('wilayas', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'wilaya' => 'required|string|max:100',
        ]);

        // Vérifier si l'utilisateur a déjà un profil distributeur
        if (Distributor::where('user_id', $validated['user_id'])->exists()) {
            return back()->with('error', 'Cet utilisateur a déjà un profil distributeur.');
        }

        Distributor::create($validated);

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributeur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Distributor $distributor)
    {
        $distributor->load(['user', 'deliveries.school', 'payments']);
        
        // Statistiques
        $stats = [
            'deliveries_count' => $distributor->deliveries()->count(),
            'total_delivered' => $distributor->deliveries()->sum('total_price'),
            'total_paid' => $distributor->payments()->sum('amount'),
            'remaining' => $distributor->deliveries()->sum('total_price') - $distributor->payments()->sum('amount'),
        ];
        
        // Livraisons récentes
        $recentDeliveries = $distributor->deliveries()
            ->with('school')
            ->latest('delivery_date')
            ->limit(10)
            ->get();
            
        // Paiements récents
        $recentPayments = $distributor->payments()
            ->latest('payment_date')
            ->limit(10)
            ->get();

        return view('admin.distributors.show', compact('distributor', 'stats', 'recentDeliveries', 'recentPayments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Distributor $distributor)
    {
        $wilayas = $this->getWilayas();
        return view('admin.distributors.edit', compact('distributor', 'wilayas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Distributor $distributor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'wilaya' => 'required|string|max:100',
        ]);

        $distributor->update($validated);

        // Mettre à jour aussi le nom de l'utilisateur associé
        if ($distributor->user) {
            $distributor->user->update(['name' => $validated['name']]);
        }

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributeur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Distributor $distributor)
    {
        // Vérifier s'il y a des livraisons ou paiements associés
        if ($distributor->deliveries()->exists() || $distributor->payments()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce distributeur car il a des livraisons ou paiements associés.');
        }
        
        $distributor->delete();

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributeur supprimé avec succès.');
    }

    /**
     * Rapport financier du distributeur
     */
    public function financialReport(Distributor $distributor)
    {
        $distributor->load(['deliveries.school', 'payments']);
        
        // Regrouper par mois
        $monthlyDeliveries = $distributor->deliveries()
            ->select(
                DB::raw('YEAR(delivery_date) as year'),
                DB::raw('MONTH(delivery_date) as month'),
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(quantity) as total_cards'),
                DB::raw('SUM(total_price) as total_amount')
            )
            ->whereNotNull('delivery_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
            
        $monthlyPayments = $distributor->payments()
            ->select(
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('SUM(amount) as total_paid')
            )
            ->whereNotNull('payment_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.distributors.financial-report', compact('distributor', 'monthlyDeliveries', 'monthlyPayments'));
    }

    /**
     * Liste des wilayas
     */
    private function getWilayas()
    {
        // Même liste que pour les écoles
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