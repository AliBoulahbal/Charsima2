<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Delivery; 
use App\Models\Distributor; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\SchoolsImport; 
use Maatwebsite\Excel\Facades\Excel; 
use Illuminate\Validation\ValidationException; 
use App\Exports\SchoolsExport;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = School::query();
        
        // 1. Calculer le nombre de livraisons
        $query->withCount('deliveries');

        // 2. Calculer le montant total livré via une sous-requête (évite erreur 1055)
        $query->addSelect([
            'total_delivered' => Delivery::select(DB::raw('COALESCE(SUM(final_price), 0)'))
                ->whereColumn('school_id', 'schools.id') 
                ->take(1)
        ]);

        // --- FILTRES ---
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('wilaya', 'like', '%' . $search . '%')
                  ->orWhere('commune', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }

        $schools = $query->orderBy('name')->paginate(20);
        
        $wilayas = $this->getWilayas(); 

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
            'name' => 'required|string|max:255|unique:schools,name',
            'wilaya' => 'required|string|max:100',
            'commune' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'nullable|string|max:255',
            'student_count' => 'nullable|integer|min:0',
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
        // 1. Charger les relations
        // S'assurer que 'payments' est inclus pour les statistiques de paiement
        $school->load('distributors', 'deliveries', 'payments'); 
        
        // 2. Calculer les statistiques
        $totalAmountDue = $school->deliveries->sum('final_price');
        $totalPaid = $school->payments->sum('amount'); // Nécessite la relation payments() dans School.php
        
        $stats = [
            'total_deliveries' => $school->deliveries->count(),
            'total_cards' => $school->deliveries->sum('quantity'), 
            'total_amount' => $totalAmountDue,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalAmountDue - $totalPaid,
        ];
        
        // 3. Récupérer les livraisons récentes (pour la vue show.blade.php)
        $recentDeliveries = $school->deliveries()
                                   ->latest('delivery_date')
                                   ->take(10)
                                   // Assurez-vous d'EAGER LOAD les relations nécessaires si elles sont utilisées dans le tableau de la vue
                                   // ->with(['distributor', 'kiosk'])
                                   ->get();

        // 4. Passer toutes les variables à la vue
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
            'name' => 'required|string|max:255|unique:schools,name,' . $school->id,
            'wilaya' => 'required|string|max:100',
            'commune' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_name' => 'nullable|string|max:255',
            'student_count' => 'nullable|integer|min:0',
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
        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', 'École supprimée avec succès.');
    }

    /**
     * Traite le fichier Excel téléchargé et lance l'importation.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
            'wilaya' => 'required|string|max:100',
        ]);
        
        $wilaya = $request->input('wilaya');

        try {
            Excel::import(new SchoolsImport($wilaya), $request->file('file'));

            return back()->with('success', "Importation des écoles pour $wilaya réussie !");

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = collect($failures)->map(function ($failure) {
                return "Ligne {$failure->row()}: " . implode(', ', $failure->errors());
            })->implode('; ');
            
            return back()->with('error', "Échec de la validation de l'importation. Détails : $errors")
                         ->withErrors(['file' => 'Erreur de validation de données dans le fichier.']);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage())
                         ->withErrors(['file' => 'Veuillez vérifier le format du fichier, les en-têtes de colonnes, et les données.']);
        }
    }
    
    /**
     * Export des écoles (Excel)
     */
    public function export()
    {
        $filename = 'ecoles_' . now()->format('Ymd_His') . '.xlsx';
        
        // CORRECTION CLÉ : Utilisation de Maatwebsite\Excel pour le téléchargement
        return Excel::download(new SchoolsExport, $filename); 
        
        // Supprimez l'ancien placeholder si vous l'aviez
        // return back()->with('error', 'La fonction d\'exportation n\'est pas encore implémentée.');
    }

    /**
     * Liste des wilayas (Helper function)
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