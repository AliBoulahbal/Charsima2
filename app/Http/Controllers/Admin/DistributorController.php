<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\User;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DistributorController extends Controller
{
    /**
     * Liste des distributeurs avec calcul de stock temps réel.
     */
    public function index(Request $request)
    {
        $query = Distributor::with(['user']);
        
        // Système de recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('wilaya', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filtre par Wilaya
        if ($request->filled('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        // Requête avec calculs SQL optimisés
        $distributors = $query->select([
            'distributors.*',
            
            // 1. TOTAL REÇUES (Tout le stock envoyé par le dépôt au distributeur)
            DB::raw("(SELECT COALESCE(SUM(quantity), 0) FROM deliveries 
                      WHERE deliveries.distributor_id = distributors.id) as total_received"),

            // 2. TOTAL LIVRÉES (Uniquement les cartes sorties vers les écoles - status = 'delivered')
            DB::raw("(SELECT COALESCE(SUM(quantity), 0) FROM deliveries 
                      WHERE deliveries.distributor_id = distributors.id 
                      AND status = 'delivered') as cards_delivered"),
            
            // 3. STATISTIQUES FINANCIÈRES
            DB::raw('(SELECT COALESCE(SUM(final_price), 0) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as total_delivered_money'),
            DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.distributor_id = distributors.id) as total_paid_money'),
            DB::raw('(SELECT COUNT(*) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as deliveries_count')
        ])
        ->orderBy('name', 'asc')
        ->paginate(20);
        
        $wilayas = $this->getWilayas();
        
        return view('admin.distributors.index', compact('distributors', 'wilayas'));
    }

    /**
     * Fiche détaillée (Show) - Correction des variables Undefined
     */
    public function show(Distributor $distributor)
    {
        $distributor->load(['user']);

        // Récupération des données pour les tableaux de bord
        $recentDeliveries = $distributor->deliveries()->with('school')->latest()->limit(10)->get();
        $recentPayments = $distributor->payments()->latest()->limit(10)->get();

        // Calculs financiers pour les boîtes de statistiques
        $total_delivered_money = $distributor->deliveries->sum('final_price');
        $total_paid_money = $distributor->payments->sum('amount');

        $stats = [
            'total_received'    => $distributor->deliveries->sum('quantity'),
            'total_distributed' => $distributor->deliveries->where('status', 'delivered')->sum('quantity'),
            'total_delivered'   => $total_delivered_money,
            'total_paid'        => $total_paid_money,
            'remaining'         => $total_delivered_money - $total_paid_money,
            'deliveries_count'  => $distributor->deliveries->count(),
        ];

        return view('admin.distributors.show', compact('distributor', 'stats', 'recentDeliveries', 'recentPayments'));
    }

    public function create()
    {
        $wilayas = $this->getWilayas();
        // Récupérer les utilisateurs "distributeur" qui n'ont pas encore de profil rattaché
        $users = User::where('role', 'distributor')
            ->whereNotExists(function($q) {
                $q->select(DB::raw(1))->from('distributors')->whereRaw('distributors.user_id = users.id');
            })->get();

        return view('admin.distributors.create', compact('wilayas', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'wilaya' => 'required',
            'phone' => 'nullable|string'
        ]);

        Distributor::create($request->all());

        return redirect()->route('admin.distributors.index')->with('success', 'Distributeur créé avec succès.');
    }

    public function edit(Distributor $distributor)
    {
        $wilayas = $this->getWilayas();
        $users = User::where('role', 'distributor')->get();
        return view('admin.distributors.edit', compact('distributor', 'wilayas', 'users'));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'wilaya' => 'required',
        ]);

        $distributor->update($request->all());

        return redirect()->route('admin.distributors.index')->with('success', 'Distributeur mis à jour.');
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        return redirect()->route('admin.distributors.index')->with('success', 'Distributeur supprimé.');
    }

    /**
     * Rapport financier (Répare l'erreur Undefined variable $monthlyPayments)
     */
    public function financialReport(Distributor $distributor)
    {
        // Stats livraisons par mois
        $monthlyDeliveries = $distributor->deliveries()
            ->select(DB::raw('YEAR(delivery_date) as year, MONTH(delivery_date) as month, SUM(quantity) as total_cards, SUM(final_price) as total_amount'))
            ->whereNotNull('delivery_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->get();
            
        // Stats paiements par mois (Correction erreur)
        $monthlyPayments = $distributor->payments()
            ->select(DB::raw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(amount) as total_paid'))
            ->whereNotNull('payment_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->get();

        return view('admin.distributors.financial-report', compact('distributor', 'monthlyDeliveries', 'monthlyPayments'));
    }

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