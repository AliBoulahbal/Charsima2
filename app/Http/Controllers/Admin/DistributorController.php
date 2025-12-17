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
        $query = Distributor::with(['user', 'payments']);
        
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
                // CORRECTION/COHÉRENCE: Utiliser final_price pour le montant livré (comme dans le modèle)
                DB::raw('(SELECT COALESCE(SUM(final_price), 0) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as total_delivered'),
                DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.distributor_id = distributors.id) as total_paid')
            ])
            ->orderByDesc('deliveries_count')
            ->paginate(20); // CORRECTION: Utiliser paginate() pour l'affichage des liens
        
        // La liste des wilayas pour le filtre (utilisée dans la vue index)
        $wilayas = $this->getWilayas();
        
        return view('admin.distributors.index', compact('distributors', 'wilayas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wilayas = $this->getWilayas();
        // Charge les utilisateurs qui ont le rôle 'distributor' et n'ont pas encore de profil distributeur
        $users = User::where('role', 'distributor')
            ->whereDoesntHave('distributorProfile')
            ->get();
        
        $distributor = new Distributor();

        return view('admin.distributors.create', compact('wilayas', 'users', 'distributor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:distributors,user_id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'wilaya' => 'required|string|max:100',
        ]);

        Distributor::create($validated);

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributeur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Distributor $distributor)
    {
        // Chargement des relations pour les accesseurs et l'affichage des détails
        $distributor->load(['user', 'deliveries.school', 'payments']);
        
        // Calcul des statistiques en utilisant les accesseurs du modèle (plus propre)
        $stats = [
            'deliveries_count' => $distributor->total_deliveries,
            'total_delivered' => $distributor->total_delivered_amount, 
            'total_paid' => $distributor->total_paid_amount,
            'remaining' => $distributor->total_remaining_amount,
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
        // CORRECTION CLÉ: Charger la liste des utilisateurs pour le formulaire (résout Undefined variable $users)
        $users = User::orderBy('name')->get(); 
        
        return view('admin.distributors.edit', compact('distributor', 'wilayas', 'users'));
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
            // Valider user_id en ignorant l'ID du distributeur actuel
            'user_id' => 'nullable|exists:users,id|unique:distributors,user_id,' . $distributor->id,
        ]);

        $distributor->update($validated);

        // Mettre à jour aussi le nom de l'utilisateur associé (optionnel)
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
                DB::raw('SUM(final_price) as total_amount')
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