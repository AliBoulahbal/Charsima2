<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = School::query();
        
        // Recherche
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('wilaya', 'like', "%{$search}%");
            });
        }
        
        // Filtre par wilaya
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        $schools = $query->withCount('deliveries')
            ->addSelect([
                'total_delivered' => Delivery::selectRaw('COALESCE(SUM(total_price), 0)')
                    ->whereColumn('school_id', 'schools.id')
            ])
            ->latest()
            ->paginate(20);
        
        // Liste des wilayas uniques pour le filtre
        $wilayas = School::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        
        return view('admin.schools.index', compact('schools', 'wilayas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wilayas = $this->getWilayas();
        return view('admin.schools.create', compact('wilayas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'required|string|max:255',
            'student_count' => 'required|integer|min:0',
            'wilaya' => 'required|string|max:100',
        ]);

        School::create($validated);

        return redirect()->route('admin.schools.index')
            ->with('success', 'École créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        $school->load(['deliveries.distributor.user', 'deliveries.payments']);
        
        // Statistiques de l'école
        $stats = [
            'total_deliveries' => $school->deliveries()->count(),
            'total_cards' => $school->deliveries()->sum('quantity'),
            'total_amount' => $school->deliveries()->sum('total_price'),
            'total_paid' => $school->deliveries()->withSum('payments', 'amount')->get()->sum('payments_sum_amount'),
        ];
        
        // Dernières livraisons
        $recentDeliveries = $school->deliveries()
            ->with(['distributor.user', 'payments'])
            ->latest('delivery_date')
            ->limit(10)
            ->get();

        return view('admin.schools.show', compact('school', 'stats', 'recentDeliveries'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        $wilayas = $this->getWilayas();
        return view('admin.schools.edit', compact('school', 'wilayas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'required|string|max:255',
            'student_count' => 'required|integer|min:0',
            'wilaya' => 'required|string|max:100',
        ]);

        $school->update($validated);

        return redirect()->route('admin.schools.index')
            ->with('success', 'École mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        // Vérifier s'il y a des livraisons associées
        if ($school->deliveries()->exists()) {
            return back()->with('error', 'Impossible de supprimer cette école car elle a des livraisons associées.');
        }
        
        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', 'École supprimée avec succès.');
    }

    /**
     * Export des écoles (PDF/Excel)
     */
    public function export(Request $request)
    {
        $query = School::query();
        
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        $schools = $query->withCount('deliveries')
            ->addSelect([
                'total_delivered' => Delivery::selectRaw('COALESCE(SUM(total_price), 0)')
                    ->whereColumn('school_id', 'schools.id')
            ])
            ->get();
        
        // La vue 'admin.schools.export' devrait gérer la génération et le téléchargement du fichier.
        return view('admin.schools.export', compact('schools'));
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


