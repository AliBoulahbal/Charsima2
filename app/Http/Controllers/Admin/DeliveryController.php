<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\School;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Delivery::with(['school', 'distributor.user']);
        
        // Filtres
        if ($request->has('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }
        
        if ($request->has('distributor_id')) {
            $query->where('distributor_id', $request->input('distributor_id'));
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->input('date_from'));
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->input('date_to'));
        }
        
        if ($request->has('wilaya')) {
            $query->whereHas('school', function($q) use ($request) {
                $q->where('wilaya', $request->input('wilaya'));
            });
        }
        
        $deliveries = $query->latest('delivery_date')->paginate(20);
        
        // Données pour les filtres
        $schools = School::orderBy('name')->get();
        $distributors = Distributor::with('user')->orderBy('name')->get();
        $wilayas = School::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        
        // Statistiques
        $stats = [
            'total' => $deliveries->total(),
            'total_quantity' => $deliveries->sum('quantity'),
            'total_amount' => $deliveries->sum('total_price'),
        ];

        return view('admin.deliveries.index', compact('deliveries', 'schools', 'distributors', 'wilayas', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     * CORRECTION: Ajout de l'instance $delivery vide.
     */
    public function create()
    {
        $schools = School::orderBy('name')->get();
        $distributors = Distributor::with('user')->orderBy('name')->get();
        
        // Correction ici : Instancier un nouveau modèle Delivery pour éviter l'erreur "Undefined variable"
        $delivery = new Delivery();
        
        // La variable $delivery est maintenant disponible dans la vue create.blade.php et _form.blade.php
        return view('admin.deliveries.create', compact('schools', 'distributors', 'delivery'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'distributor_id' => 'required|exists:distributors,id',
            'delivery_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
        ]);

        // Calculer le prix total
        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];
        
        Delivery::create($validated);

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Livraison créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['school', 'distributor.user']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery)
    {
        $schools = School::orderBy('name')->get();
        $distributors = Distributor::with('user')->orderBy('name')->get();
        
        return view('admin.deliveries.edit', compact('delivery', 'schools', 'distributors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'distributor_id' => 'required|exists:distributors,id',
            'delivery_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
        ]);

        // Calculer le prix total
        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];
        
        $delivery->update($validated);

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Livraison mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Livraison supprimée avec succès.');
    }

    /**
     * Export des livraisons
     */
    public function export(Request $request)
    {
        $query = Delivery::with(['school', 'distributor.user']);
        
        // Appliquer les mêmes filtres que l'index (si fournis)
        // ... (Filtres appliqués pour l'exportation)
        
        $deliveries = $query->latest('delivery_date')->get();
        
        return view('admin.deliveries.export', compact('deliveries'));
    }

    /**
     * Statistiques des livraisons
     */
    public function statistics(Request $request)
    {
        // Statistiques par mois
        $monthlyStats = Delivery::select(
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
            ->limit(12)
            ->get();
            
        // Statistiques par wilaya
        $wilayaStats = Delivery::join('schools', 'deliveries.school_id', '=', 'schools.id')
            ->select(
                'schools.wilaya',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.total_price) as total_amount')
            )
            ->groupBy('schools.wilaya')
            ->orderByDesc('total_amount')
            ->get();
            
        // Top écoles
        $topSchools = Delivery::join('schools', 'deliveries.school_id', '=', 'schools.id')
            ->select(
                'schools.id',
                'schools.name',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.total_price) as total_amount')
            )
            ->groupBy('schools.id', 'schools.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
            
        // Top distributeurs
        $topDistributors = Delivery::join('distributors', 'deliveries.distributor_id', '=', 'distributors.id')
            ->select(
                'distributors.id',
                'distributors.name',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.total_price) as total_amount')
            )
            ->groupBy('distributors.id', 'distributors.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        return view('admin.deliveries.statistics', compact(
            'monthlyStats', 'wilayaStats', 'topSchools', 'topDistributors'
        ));
    }
}