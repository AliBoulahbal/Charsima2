<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\School;
use App\Models\Distributor;
use App\Models\Kiosk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Ajouté pour la validation conditionnelle
use App\Exports\DeliveriesExport; // Assurez-vous d'importer votre classe d'exportation
use Maatwebsite\Excel\Facades\Excel; // Nécessaire pour l'export Excel

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Charger school, distributor, et kiosk pour l'affichage de l'index
        $query = Delivery::with(['school', 'distributor.user', 'kiosk']);
        
        // Filtres : Utilisation de filled() pour la robustesse et la sécurité
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }
        
        if ($request->filled('distributor_id')) {
            $query->where('distributor_id', $request->input('distributor_id'));
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->input('date_to'));
        }
        
        if ($request->filled('wilaya')) {
            $wilaya = $request->input('wilaya');
            $query->where(function ($q) use ($wilaya) {
                // Filtrer par la wilaya de l'école (livraison école)
                $q->whereHas('school', function($q_school) use ($wilaya) {
                    $q_school->where('wilaya', $wilaya);
                })
                // OU par la wilaya directement sur le modèle Delivery (kiosque/online)
                ->orWhere('deliveries.wilaya', $wilaya);
            });
        }
        
        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->input('delivery_type'));
        }
        
        $deliveries = $query->latest('delivery_date')->paginate(20);
        
        // Données pour les filtres
        $schools = School::orderBy('name')->get();
        $distributors = Distributor::with('user')->orderBy('name')->get();
        
        // Collecter les wilayas uniques (pour le filtre)
        $wilayas = School::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya')
            ->merge(Delivery::select('wilaya')->distinct()->whereNotNull('wilaya')->pluck('wilaya'))
            ->sort()
            ->unique();
        
        // Statistiques pour l'index
        $stats = [
            'total' => $deliveries->total(),
            'total_quantity' => $deliveries->sum('quantity'),
            'total_amount' => $deliveries->sum('final_price'),
        ];

        return view('admin.deliveries.index', compact('deliveries', 'schools', 'distributors', 'wilayas', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::orderBy('name')->get();
        $distributors = Distributor::with('user')->orderBy('name')->get();
        $kiosks = Kiosk::orderBy('name')->get(); 
        
        // CORRECTION: Appel de la méthode manquante
        $wilayas = $this->getWilayas(); 

        $delivery = new Delivery();
        
        return view('admin.deliveries.create', compact('schools', 'distributors', 'kiosks', 'delivery', 'wilayas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'delivery_type' => 'required|in:school,kiosk,online,teacher_free',
            'delivery_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'total_price' => 'required|numeric', 
            'final_price' => 'required|numeric',
            
            // Champs Client/Enseignant
            'teacher_name' => 'nullable|string|max:255',
            'teacher_phone' => 'nullable|string|max:20',
            'customer_cin' => 'nullable|string|max:255',
            'wilaya' => 'nullable|string|max:100',
            'teacher_subject' => 'nullable|string|max:255',
            'teacher_email' => 'nullable|email|max:255',
            'delivery_address' => 'nullable|string|max:500', // Ajouté pour les livraisons directes
        ];

        $type = $request->input('delivery_type');

        // Validation CONDITIONNELLE
        if ($type === 'school') {
            $rules['school_id'] = 'required|exists:schools,id';
            $rules['distributor_id'] = 'required|exists:distributors,id';
        } elseif ($type === 'kiosk') {
            $rules['kiosk_id'] = 'required|exists:kiosks,id';
            $rules['teacher_name'] = 'required|string|max:255';
            $rules['teacher_phone'] = 'required|string|max:20';
        } elseif ($type === 'online') {
            $rules['teacher_name'] = 'required|string|max:255';
            $rules['teacher_phone'] = 'required|string|max:20';
            $rules['wilaya'] = 'required|string|max:100'; // Wilaya requise pour l'adresse de livraison
        } elseif ($type === 'teacher_free') {
            $rules['school_id'] = 'required|exists:schools,id';
            $rules['teacher_name'] = 'required|string|max:255';
            $rules['teacher_phone'] = 'required|string|max:20';
        }
        
        // Les IDs doivent être nullable dans la base de données
        $rules['school_id'] = $rules['school_id'] ?? 'nullable|exists:schools,id';
        $rules['distributor_id'] = $rules['distributor_id'] ?? 'nullable|exists:distributors,id';
        $rules['kiosk_id'] = $rules['kiosk_id'] ?? 'nullable|exists:kiosks,id';

        $validated = $request->validate($rules);
        
        // Nettoyage des IDs non pertinents (essentiel)
        if (!in_array($type, ['school', 'teacher_free'])) {
            $validated['school_id'] = null;
        }
        
        if ($type !== 'school') {
            $validated['distributor_id'] = null;
        }
        
        if ($type !== 'kiosk') {
            $validated['kiosk_id'] = null;
        }
        
        // Mettre à jour la wilaya si l'école est sélectionnée (Wilaya prioritaire de l'école)
        if ($validated['school_id']) {
            $school = School::find($validated['school_id']);
            if ($school) {
                $validated['wilaya'] = $school->wilaya;
            }
        }


        Delivery::create($validated);

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Livraison créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['school', 'distributor.user', 'kiosk.user']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery)
    {
        $schools = School::orderBy('name')->get();
        $distributors = Distributor::with('user')->orderBy('name')->get();
        $kiosks = Kiosk::orderBy('name')->get(); 
        $wilayas = $this->getWilayas(); // CORRECTION: Appel de la méthode

        return view('admin.deliveries.edit', compact('delivery', 'schools', 'distributors', 'kiosks', 'wilayas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $rules = [
            'delivery_type' => 'required|in:school,kiosk,online,teacher_free',
            'delivery_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|integer|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'total_price' => 'required|numeric', 
            'final_price' => 'required|numeric',
            'teacher_name' => 'nullable|string|max:255',
            'teacher_phone' => 'nullable|string|max:20',
            'customer_cin' => 'nullable|string|max:255',
            'wilaya' => 'nullable|string|max:100',
            'teacher_subject' => 'nullable|string|max:255',
            'teacher_email' => 'nullable|email|max:255',
            'delivery_address' => 'nullable|string|max:500',
        ];

        $type = $request->input('delivery_type');

        if ($type === 'school') {
            $rules['school_id'] = 'required|exists:schools,id';
            $rules['distributor_id'] = 'required|exists:distributors,id';
        } elseif ($type === 'kiosk') {
            $rules['kiosk_id'] = 'required|exists:kiosks,id';
            $rules['teacher_name'] = 'required|string|max:255';
            $rules['teacher_phone'] = 'required|string|max:20';
        } elseif ($type === 'online') {
            $rules['teacher_name'] = 'required|string|max:255';
            $rules['teacher_phone'] = 'required|string|max:20';
            $rules['wilaya'] = 'required|string|max:100'; 
        } elseif ($type === 'teacher_free') {
            $rules['school_id'] = 'required|exists:schools,id';
            $rules['teacher_name'] = 'required|string|max:255';
            $rules['teacher_phone'] = 'required|string|max:20';
        }
        
        $validated = $request->validate($rules);
        
        // Nettoyage des IDs non pertinents
        if (!in_array($type, ['school', 'teacher_free'])) {
            $validated['school_id'] = null;
        }
        
        if ($type !== 'school') {
            $validated['distributor_id'] = null;
        }
        
        if ($type !== 'kiosk') {
            $validated['kiosk_id'] = null;
        }
        
        // Mettre à jour la wilaya si l'école est sélectionnée (Wilaya prioritaire de l'école)
        if ($validated['school_id']) {
            $school = School::find($validated['school_id']);
            if ($school) {
                $validated['wilaya'] = $school->wilaya;
            }
        }
        
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
     * Export des livraisons (Excel et PDF)
     */
    public function export(Request $request)
    {
        $filters = $request->except(['_token', 'format']); // Exclure le token et le format
        $format = $request->input('format', 'excel');

        if ($format === 'excel') {
            $filename = 'livraisons-' . now()->format('Ymd_His') . '.xlsx';
            
            // Assurez-vous que la classe DeliveriesExport existe et applique les filtres
            return Excel::download(new DeliveriesExport($filters), $filename);
            
        } elseif ($format === 'pdf') {
            // ... (logique PDF)
            // Note: Vous devez toujours obtenir la collection en utilisant la logique de filtre
            $export = new DeliveriesExport($filters);
            $deliveries = $export->collection();

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.deliveries.export_pdf', compact('deliveries'));
            
            $filename = 'livraisons-' . now()->format('Ymd_His') . '.pdf';
            return $pdf->download($filename);
        }
        
        return back()->with('error', 'Format d\'exportation non supporté.');
    }

    /**
     * Statistiques des livraisons
     */
    public function statistics(Request $request)
    {
        // ... (logique des statistiques)
        // Code basé sur le snippet fourni (assurez-vous que toutes les jointures fonctionnent)
        
        // 1. Statistiques par mois
        $monthlyStats = Delivery::select(
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
            ->limit(12)
            ->get();
            
        // 2. Statistiques par wilaya (basé sur l'école)
        $wilayaStats = Delivery::join('schools', 'deliveries.school_id', '=', 'schools.id')
            ->select(
                'schools.wilaya',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.final_price) as total_amount')
            )
            ->groupBy('schools.wilaya')
            ->orderByDesc('total_amount')
            ->get();
            
        // 3. Top écoles
        $topSchools = Delivery::join('schools', 'deliveries.school_id', '=', 'schools.id')
            ->select(
                'schools.id',
                'schools.name',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.final_price) as total_amount')
            )
            ->groupBy('schools.id', 'schools.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();
            
        // 4. Top distributeurs
        $topDistributors = Delivery::join('distributors', 'deliveries.distributor_id', '=', 'distributors.id')
            ->select(
                'distributors.id',
                'distributors.name',
                DB::raw('COUNT(*) as deliveries_count'),
                DB::raw('SUM(deliveries.quantity) as total_cards'),
                DB::raw('SUM(deliveries.final_price) as total_amount')
            )
            ->groupBy('distributors.id', 'distributors.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        return view('admin.deliveries.statistics', compact(
            'monthlyStats', 'wilayaStats', 'topSchools', 'topDistributors'
        ));
    }
    
    /**
     * API endpoint pour récupérer les écoles filtrées par wilaya.
     */
    public function getSchoolsByWilaya(Request $request)
    {
        $wilaya = $request->input('wilaya');

        if (!$wilaya) {
            return response()->json(['schools' => []]);
        }

        $schools = School::where('wilaya', $wilaya)
            ->select('id', 'name', 'wilaya')
            ->orderBy('name')
            ->get();

        return response()->json(['schools' => $schools]);
    }


    /**
     * Liste des wilayas (Helper function)
     * CORRECTION: AJOUT DE LA MÉTHODE MANQUANTE
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