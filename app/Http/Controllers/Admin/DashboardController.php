<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\User;
use App\Models\School;
use App\Models\Distributor;
use App\Models\Kiosk; // Importé si ce n'est pas déjà fait
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administration.
     */
    public function index()
    {
        try {
            // 1. Statistiques globales
            $totalDeliveries = Delivery::count();
            $totalCards = Delivery::sum('quantity');
            $totalExpected = Delivery::sum('total_price'); // Total avant remise
            $totalPaid = Payment::sum('amount');
            $remaining = $totalExpected - $totalPaid;

            $distributorCount = User::where('role', 'distributor')->count();
            $schoolCount = School::count();
            $kioskCount = Kiosk::count(); // Ajouté si le modèle Kiosk est utilisé

            // 2. Top Distributeurs (inchangé)
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
                ->map(fn($distributor) => 
                    tap($distributor, fn($d) => $d->total_due = ($d->total_delivered ?? 0) - ($d->total_paid ?? 0))
                );

            // 3. Top Écoles (inchangé)
            $topSchools = School::withCount('deliveries')
                ->addSelect([
                    'total_delivered' => Delivery::selectRaw('COALESCE(SUM(total_price), 0)')
                        ->whereColumn('school_id', 'schools.id')
                ])
                ->orderByDesc('deliveries_count')
                ->limit(10)
                ->get();

            // 4. Dernières Livraisons
            // CORRECTION: Ajout de 'kiosk' au with()
            $recentDeliveries = Delivery::with(['school', 'distributor.user', 'kiosk'])
                ->orderByDesc('delivery_date')
                ->limit(10)
                ->get();

            // 5. Statistiques par Wilaya (inchangé)
            $wilayaStats = Distributor::select('wilaya', DB::raw('COUNT(*) as distributor_count'))
                ->groupBy('wilaya')
                ->orderByDesc('distributor_count')
                ->get();

            return view('admin.dashboard', compact(
                'totalCards', 'totalDeliveries', 'totalExpected', 'totalPaid', 'remaining',
                'distributorCount', 'schoolCount', 'kioskCount',
                'topDistributors', 'topSchools',
                'recentDeliveries', 'wilayaStats'
            ));

        } catch (\Exception $e) {
            // Logique de gestion des erreurs (inchangée)
            return view('admin.dashboard', [
                'totalCards' => 0,
                'totalDeliveries' => 0,
                'totalExpected' => 0,
                'totalPaid' => 0,
                'remaining' => 0,
                'distributorCount' => 0,
                'schoolCount' => 0,
                'kioskCount' => 0,
                'topDistributors' => collect([]),
                'topSchools' => collect([]),
                'recentDeliveries' => collect([]),
                'wilayaStats' => collect([]),
            ]);
        }
    }
}