<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Distributor;
use App\Models\Kiosk;
use App\Models\School;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['distributor.user', 'kiosk', 'school']);
        
        // Filtres
        if ($request->has('distributor_id')) {
            $query->where('distributor_id', $request->input('distributor_id'));
        }
        
        if ($request->has('kiosk_id')) {
            $query->where('kiosk_id', $request->input('kiosk_id'));
        }
        
        if ($request->has('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }
        
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }
        
        if ($request->has('method')) {
            $query->where('method', $request->input('method'));
        }
        
        $payments = $query->latest('payment_date')->paginate(20);
        
        // Données pour les filtres
        $distributors = Distributor::with('user')->orderBy('name')->get();
        $kiosks = Kiosk::where('is_active', true)->orderBy('name')->get();
        $schools = School::orderBy('name')->get();
        $methods = ['cash' => 'Espèces', 'check' => 'Chèque', 'transfer' => 'Virement', 'card' => 'Carte', 'post_office' => 'Poste', 'other' => 'Autre'];
        
        // Liste des wilayas
        $wilayas = Payment::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        
        // Statistiques
        $stats = [
            'total' => $payments->total(),
            'total_amount' => $payments->sum('amount'),
        ];

        return view('admin.payments.index', compact(
            'payments', 'distributors', 'kiosks', 'schools', 'methods', 'wilayas', 'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $distributors = Distributor::with(['user', 'deliveries'])->orderBy('name')->get();
        $methods = ['cash' => 'Espèces', 'check' => 'Chèque', 'transfer' => 'Virement', 'card' => 'Carte', 'post_office' => 'Poste', 'other' => 'Autre'];
        
        // Ajouter les écoles, wilayas et kiosques
        $schools = School::orderBy('name')->get();
        $wilayas = School::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        $kiosks = Kiosk::where('is_active', true)->orderBy('name')->get();
        
        $payment = new Payment(); 
        
        return view('admin.payments.create', compact(
            'distributors', 'methods', 'payment', 'schools', 'wilayas', 'kiosks'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_type' => 'required|in:distributor,kiosk,online,other',
            'distributor_id' => 'nullable|required_if:payment_type,distributor|exists:distributors,id',
            'kiosk_id' => 'nullable|required_if:payment_type,kiosk|exists:kiosks,id',
            'school_id' => 'required|exists:schools,id',
            'delivery_id' => 'nullable|exists:deliveries,id',
            'amount' => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'method' => 'required|string|in:cash,check,transfer,card,post_office,other',
            'wilaya' => 'required|string|max:100',
            'school_name' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Récupérer l'école
        $school = School::find($validated['school_id']);
        
        // Créer le paiement
        $payment = Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Paiement enregistré avec succès.')
            ->with('payment_details', [
                'id' => $payment->id,
                'school' => $school->name,
                'wilaya' => $validated['wilaya'],
                'amount' => number_format($validated['amount'], 0, ',', ' ') . ' DA',
                'date' => $validated['payment_date'],
                'reference' => $validated['reference_number'] ?? 'N/A',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['distributor.user', 'kiosk', 'school', 'delivery']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $distributors = Distributor::with('user')->orderBy('name')->get();
        $kiosks = Kiosk::where('is_active', true)->orderBy('name')->get();
        $schools = School::orderBy('name')->get();
        $wilayas = School::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        $methods = ['cash' => 'Espèces', 'check' => 'Chèque', 'transfer' => 'Virement', 'card' => 'Carte', 'post_office' => 'Poste', 'other' => 'Autre'];
        
        return view('admin.payments.edit', compact(
            'payment', 'distributors', 'kiosks', 'schools', 'wilayas', 'methods'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_type' => 'required|in:distributor,kiosk,online,other',
            'distributor_id' => 'nullable|required_if:payment_type,distributor|exists:distributors,id',
            'kiosk_id' => 'nullable|required_if:payment_type,kiosk|exists:kiosks,id',
            'school_id' => 'required|exists:schools,id',
            'delivery_id' => 'nullable|exists:deliveries,id',
            'amount' => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'method' => 'required|string|in:cash,check,transfer,card,post_office,other',
            'wilaya' => 'required|string|max:100',
            'school_name' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }

    /**
     * Export des paiements
     */
    public function export(Request $request)
    {
        $query = Payment::with(['distributor.user', 'kiosk', 'school']);
        
        // Appliquer les mêmes filtres que l'index
        if ($request->has('distributor_id')) {
            $query->where('distributor_id', $request->input('distributor_id'));
        }
        
        if ($request->has('kiosk_id')) {
            $query->where('kiosk_id', $request->input('kiosk_id'));
        }
        
        if ($request->has('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }
        
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }
        
        $payments = $query->latest('payment_date')->get();
        
        return view('admin.payments.export', compact('payments'));
    }

    /**
     * Rapport financier
     */
    public function financialReport(Request $request)
    {
        // Paiements par mois
        $monthlyPayments = Payment::select(
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('COUNT(*) as payments_count'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('GROUP_CONCAT(DISTINCT method) as methods')
            )
            ->whereNotNull('payment_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
            
        // Paiements par méthode
        $methodStats = Payment::select(
                'method',
                DB::raw('COUNT(*) as payments_count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('method')
            ->get();
            
        // Top distributeurs (qui ont payé le plus)
        $topDistributors = Payment::join('distributors', 'payments.distributor_id', '=', 'distributors.id')
            ->select(
                'distributors.id',
                'distributors.name',
                'distributors.wilaya',
                DB::raw('COUNT(*) as payments_count'),
                DB::raw('SUM(payments.amount) as total_paid')
            )
            ->whereNotNull('payments.distributor_id')
            ->groupBy('distributors.id', 'distributors.name', 'distributors.wilaya')
            ->orderByDesc('total_paid')
            ->limit(10)
            ->get();
            
        // Top kiosques (qui ont payé le plus)
        $topKiosks = Payment::join('kiosks', 'payments.kiosk_id', '=', 'kiosks.id')
            ->select(
                'kiosks.id',
                'kiosks.name',
                'kiosks.wilaya',
                DB::raw('COUNT(*) as payments_count'),
                DB::raw('SUM(payments.amount) as total_paid')
            )
            ->whereNotNull('payments.kiosk_id')
            ->groupBy('kiosks.id', 'kiosks.name', 'kiosks.wilaya')
            ->orderByDesc('total_paid')
            ->limit(10)
            ->get();
            
        // Comparaison livraisons vs paiements par école
        $schoolComparison = School::select([
                'schools.*',
                DB::raw('(SELECT COALESCE(SUM(total_price), 0) FROM deliveries WHERE deliveries.school_id = schools.id) as total_delivered'),
                DB::raw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.school_id = schools.id) as total_paid')
            ])
            ->havingRaw('total_delivered > 0 OR total_paid > 0')
            ->orderByDesc('total_delivered')
            ->limit(20)
            ->get();

        return view('admin.payments.financial-report', compact(
            'monthlyPayments', 'methodStats', 'topDistributors', 'topKiosks', 'schoolComparison'
        ));
    }

    /**
     * Rapport des paiements par école/wilaya
     */
    public function schoolPaymentsReport(Request $request)
    {
        $query = Payment::with(['school', 'distributor.user', 'kiosk']);
        
        // Filtre par wilaya
        if ($request->has('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        // Filtre par école
        if ($request->has('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }
        
        // Filtre par période
        if ($request->has('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }
        
        // Regrouper par école
        $schoolPayments = $query->select([
                'school_id',
                'school_name',
                'wilaya',
                DB::raw('COUNT(*) as payments_count'),
                DB::raw('SUM(amount) as total_paid')
            ])
            ->groupBy('school_id', 'school_name', 'wilaya')
            ->orderByDesc('total_paid')
            ->paginate(20);
        
        // Statistiques par wilaya
        $wilayaStats = Payment::select(
                'wilaya',
                DB::raw('COUNT(*) as payments_count'),
                DB::raw('SUM(amount) as total_paid')
            )
            ->whereNotNull('wilaya')
            ->groupBy('wilaya')
            ->orderByDesc('total_paid')
            ->get();
        
        $schools = School::orderBy('name')->get();
        $wilayas = School::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        
        return view('admin.payments.school-report', compact('schoolPayments', 'wilayaStats', 'schools', 'wilayas'));
    }

    /**
     * Paiement pour une livraison spécifique
     */
    public function createForDelivery(Delivery $delivery)
    {
        $methods = ['cash' => 'Espèces', 'check' => 'Chèque', 'transfer' => 'Virement', 'card' => 'Carte', 'post_office' => 'Poste', 'other' => 'Autre'];
        $wilayas = School::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        
        // Calculer le montant déjà payé pour cette livraison
        $paidAmount = Payment::where('delivery_id', $delivery->id)->sum('amount');
        $remainingAmount = $delivery->final_price - $paidAmount;
        
        return view('admin.payments.create-for-delivery', compact(
            'delivery', 'methods', 'wilayas', 'paidAmount', 'remainingAmount'
        ));
    }

    /**
     * Obtenir les livraisons pour une école (API)
     */
    public function getDeliveriesForSchool(School $school)
    {
        $deliveries = $school->deliveries()
            ->select('id', 'transaction_id', 'quantity', 'total_price', 'final_price', 'delivery_date', 'delivery_type')
            ->orderBy('delivery_date', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($delivery) {
                return [
                    'id' => $delivery->id,
                    'text' => sprintf(
                        "Livraison #%s - %s - %d cartes - %s DA - %s",
                        $delivery->transaction_id,
                        $delivery->delivery_type_formatted,
                        $delivery->quantity,
                        number_format($delivery->final_price, 0, ',', ' '),
                        $delivery->delivery_date->format('d/m/Y')
                    ),
                    'quantity' => $delivery->quantity,
                    'amount' => $delivery->final_price,
                    'date' => $delivery->delivery_date->format('d/m/Y'),
                    'transaction_id' => $delivery->transaction_id,
                    'type' => $delivery->delivery_type_formatted,
                ];
            });

        return response()->json([
            'success' => true,
            'deliveries' => $deliveries,
        ]);
    }
}