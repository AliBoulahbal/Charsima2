<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\User;
use App\Models\School;
use App\Models\Distributor;
use App\Models\Kiosk; // <-- AJOUT DE L'IMPORT KIOSK
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Le constructeur est vide car le middleware est géré au niveau des routes.
     */
    public function __construct()
    {
        // Le middleware d'authentification et de rôle est géré dans routes/web.php
    }

    /**
     * Affiche le tableau de bord de l'administration.
     */
    public function index()
    {
        try {
            // 1. Statistiques globales
            $totalDeliveries = Delivery::count();
            $totalCards = Delivery::sum('quantity');
            $totalExpected = Delivery::sum('total_price');
            $totalPaid = Payment::sum('amount');
            $remaining = $totalExpected - $totalPaid;

            $distributorCount = User::where('role', 'distributor')->count();
            $schoolCount = School::count();
            $kioskCount = Kiosk::count(); // <-- CALCUL DU NOMBRE TOTAL DE KIOSQUES

            // 2. Top Distributeurs (calculé avec les totaux dans la requête)
            $topDistributors = Distributor::with(['user', 'deliveries'])
                ->select([
                    'distributors.*',
                    DB::raw('(SELECT COUNT(*) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as deliveries_count'),
                    DB::raw('(SELECT COALESCE(SUM(total_price), 0) FROM deliveries WHERE deliveries.distributor_id = distributors.id) as total_delivered'),
                    DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.distributor_id = distributors.id) as total_paid')
                ])
                ->orderByDesc('deliveries_count')
                ->limit(10)
                ->get()
                // Calculer le solde dû après la récupération des données
                ->map(fn($distributor) => 
                    tap($distributor, fn($d) => $d->total_due = ($d->total_delivered ?? 0) - ($d->total_paid ?? 0))
                );

            // 3. Top Écoles (calculé avec les totaux dans la requête)
            $topSchools = School::withCount('deliveries')
                ->addSelect([
                    'total_delivered' => Delivery::selectRaw('COALESCE(SUM(total_price), 0)')
                        ->whereColumn('school_id', 'schools.id')
                ])
                ->orderByDesc('deliveries_count')
                ->limit(10)
                ->get();

            // 4. Dernières Livraisons
            $recentDeliveries = Delivery::with(['school', 'distributor.user'])
                ->orderByDesc('delivery_date')
                ->limit(10)
                ->get();

            // 5. Statistiques par Wilaya (Distributeurs)
            $wilayaStats = Distributor::select('wilaya', DB::raw('COUNT(*) as distributor_count'))
                ->groupBy('wilaya')
                ->orderByDesc('distributor_count')
                ->get();

            return view('admin.dashboard', compact(
                'totalCards', 'totalDeliveries', 'totalExpected', 'totalPaid', 'remaining',
                'distributorCount', 'schoolCount', 'kioskCount', // <-- TRANSMISSION DE LA NOUVELLE VARIABLE
                'topDistributors', 'topSchools',
                'recentDeliveries', 'wilayaStats'
            ));

        } catch (\Exception $e) {
            // ... (Gestion des erreurs)
            return view('admin.dashboard', [
                'totalCards' => 0,
                'totalDeliveries' => 0,
                'totalExpected' => 0,
                'totalPaid' => 0,
                'remaining' => 0,
                'distributorCount' => 0,
                'schoolCount' => 0,
                'kioskCount' => 0, // <-- Assurer que la variable est présente même en cas d'erreur
                'topDistributors' => collect([]),
                'topSchools' => collect([]),
                'recentDeliveries' => collect([]),
                'wilayaStats' => collect([]),
            ]);
        }
    }
}