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
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel; // Nécessaire pour l'export Excel
// use App\Exports\PaymentsExport; // Décommentez si la classe existe

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['distributor.user', 'kiosk', 'school']);
        
        if ($request->filled('distributor_id')) {
            $query->where('distributor_id', $request->input('distributor_id'));
        }
        
        if ($request->filled('kiosk_id')) {
            $query->where('kiosk_id', $request->input('kiosk_id'));
        }
        
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }
        
        if ($request->filled('wilaya')) {
            $query->where('wilaya', $request->input('wilaya'));
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }
        
        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }
        
        $payments = $query->latest('payment_date')->paginate(20);
        
        $distributors = Distributor::with('user')->orderBy('name')->get();
        $kiosks = Kiosk::where('is_active', true)->orderBy('name')->get();
        $schools = School::orderBy('name')->get();
        $methods = ['cash' => 'Espèces', 'check' => 'Chèque', 'transfer' => 'Virement', 'card' => 'Carte', 'post_office' => 'Poste', 'other' => 'Autre'];
        $wilayas = Payment::select('wilaya')->distinct()->orderBy('wilaya')->pluck('wilaya');
        
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
        $distributors = Distributor::with('user')->orderBy('name')->get();
        $kiosks = Kiosk::where('is_active', true)->orderBy('name')->get();
        $schools = School::orderBy('name')->get();
        $wilayas = $this->getWilayas();
        
        $methods = ['cash' => 'Espèces', 'check' => 'Chèque', 'transfer' => 'Virement', 'card' => 'Carte', 'post_office' => 'Poste', 'other' => 'Autre'];
        
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
        $type = $request->input('payment_type');

        $rules = [
            'payment_type' => 'required|in:distributor,kiosk,online,other',
            'distributor_id' => 'nullable|required_if:payment_type,distributor|exists:distributors,id',
            'kiosk_id' => 'nullable|required_if:payment_type,kiosk|exists:kiosks,id',
            
            'school_id' => ['nullable', Rule::requiredIf($type === 'distributor'), 'exists:schools,id'], 
            'delivery_id' => 'nullable|exists:deliveries,id',
            
            'amount' => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'method' => 'required|string|in:cash,check,transfer,card,post_office,other',
            'wilaya' => 'nullable|string|max:100',
            'school_name' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ];

        $validated = $request->validate($rules);
        
        if ($type !== 'distributor') {
            $validated['distributor_id'] = null;
        }
        if ($type !== 'kiosk') {
            $validated['kiosk_id'] = null;
        }
        
        if ($type !== 'distributor') {
            $validated['school_id'] = null;
            $validated['school_name'] = null;
        } else {
            if ($validated['school_id']) {
                $school = School::find($validated['school_id']);
                $validated['school_name'] = $school->name;
                $validated['wilaya'] = $school->wilaya; 
            }
        }
        
        Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Paiement enregistré avec succès.');
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
        $wilayas = $this->getWilayas();
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
        $type = $request->input('payment_type');

        $rules = [
            'payment_type' => 'required|in:distributor,kiosk,online,other',
            'distributor_id' => 'nullable|required_if:payment_type,distributor|exists:distributors,id',
            'kiosk_id' => 'nullable|required_if:payment_type,kiosk|exists:kiosks,id',
            'school_id' => ['nullable', Rule::requiredIf($type === 'distributor'), 'exists:schools,id'], 
            'delivery_id' => 'nullable|exists:deliveries,id',
            'amount' => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'method' => 'required|string|in:cash,check,transfer,card,post_office,other',
            'wilaya' => 'nullable|string|max:100',
            'school_name' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ];
        
        $validated = $request->validate($rules);
        
        if ($type !== 'distributor') {
            $validated['distributor_id'] = null;
        }
        if ($type !== 'kiosk') {
            $validated['kiosk_id'] = null;
        }
        
        if ($type !== 'distributor') {
            $validated['school_id'] = null;
            $validated['school_name'] = null;
        } else {
            if ($validated['school_id']) {
                $school = School::find($validated['school_id']);
                $validated['school_name'] = $school->name;
                $validated['wilaya'] = $school->wilaya;
            }
        }
        
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
     * Export des paiements (Excel/PDF)
     */
    public function export(Request $request)
    {
        $query = Payment::with(['distributor.user', 'kiosk', 'school', 'delivery']);
        
        if ($request->filled('distributor_id')) {
            $query->where('distributor_id', $request->input('distributor_id'));
        }
        if ($request->filled('kiosk_id')) {
            $query->where('kiosk_id', $request->input('kiosk_id'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->input('date_to'));
        }
        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }

        $format = $request->input('format', 'excel');
        
        if ($format === 'excel') {
            $filename = 'paiements-' . now()->format('Ymd_His') . '.xlsx';
            // NOTE: Ceci suppose que App\Exports\PaymentsExport existe et utilise la requête $query
            return Excel::download(new \App\Exports\PaymentsExport($query), $filename); 
            
        } elseif ($format === 'pdf') {
            $payments = $query->latest('payment_date')->get();
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.payments.export_pdf', compact('payments'));
            
            $filename = 'paiements-' . now()->format('Ymd_His') . '.pdf';
            return $pdf->download($filename);
        }
        
        return back()->with('error', 'Format d\'exportation non supporté.');
    }


    /**
     * Rapport Financier complet (Distributeurs, Kiosques, Vente Libre)
     */
    public function financialReport(Request $request)
    {
        // --- 1. GLOBAL STATS ---
        $totalPayments = Payment::sum('amount');
        $totalRevenue = Delivery::sum('final_price'); // Montant total facturé (revenu)
        $netCashFlow = $totalPayments - $totalRevenue;

        // --- 2. PAYMENTS BREAKDOWN BY TYPE (CORRIGÉ POUR L'HISTORIQUE) ---
        // Déduit le type de paiement pour les anciens enregistrements (où payment_type est NULL)
        $paymentsByType = Payment::select(
            DB::raw("
                CASE 
                    WHEN payments.payment_type IS NOT NULL THEN payments.payment_type
                    WHEN payments.distributor_id IS NOT NULL THEN 'distributor'
                    WHEN payments.kiosk_id IS NOT NULL THEN 'kiosk'
                    -- Si le type est NULL et sans partenaire, on l'assimile à 'online'
                    ELSE 'online' 
                END AS inferred_payment_type
            "),
            DB::raw('SUM(payments.amount) as total_amount')
        )
        ->groupBy(DB::raw('inferred_payment_type'))
        ->pluck('total_amount', 'inferred_payment_type');

        // Distribution des totaux basés sur les clés inférées
        $distributorPayments = $paymentsByType['distributor'] ?? 0;
        $kioskPayments = $paymentsByType['kiosk'] ?? 0;
        $onlinePayments = $paymentsByType['online'] ?? 0;
        $otherPayments = $paymentsByType['other'] ?? 0; // Seuls les paiements explicitement marqués 'other'
        
        // --- 3. DETAILED DISTRIBUTOR BALANCES ---
        $distributorBalances = Distributor::leftJoin('deliveries', 'distributors.id', '=', 'deliveries.distributor_id')
            ->leftJoin('payments', 'distributors.id', '=', 'payments.distributor_id')
            ->select(
                'distributors.id',
                'distributors.name',
                'distributors.wilaya',
                DB::raw('COALESCE(SUM(deliveries.final_price), 0) as total_delivered'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid')
            )
            ->groupBy('distributors.id', 'distributors.name', 'distributors.wilaya')
            ->orderByDesc('total_delivered')
            ->get();
            
        // --- 4. DETAILED KIOSK BALANCES ---
        $kioskBalances = Kiosk::leftJoin('deliveries', 'kiosks.id', '=', 'deliveries.kiosk_id')
            ->leftJoin('payments', 'kiosks.id', '=', 'payments.kiosk_id')
            ->select(
                'kiosks.id',
                'kiosks.name',
                'kiosks.wilaya',
                DB::raw('COALESCE(SUM(deliveries.final_price), 0) as total_delivered'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid')
            )
            ->groupBy('kiosks.id', 'kiosks.name', 'kiosks.wilaya')
            ->orderByDesc('total_delivered')
            ->get();

        // --- 5. MONTHLY TRENDS ---
        $monthlyPayments = Payment::select(
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->whereNotNull('payment_date')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
            
        // --- 6. Return data to the view ---
        return view('admin.payments.financial-report', compact(
            'totalPayments', 
            'totalRevenue', 
            'netCashFlow',
            'distributorPayments', 
            'kioskPayments', 
            'onlinePayments',
            'otherPayments',
            'distributorBalances', 
            'kioskBalances',
            'monthlyPayments'
        ));
    }

    // /**
    //  * Display the specified resource.
    //  * CORRECTION: La méthode show() a été conservée au début du fichier.
    //  * Si vous la retrouvez ici, C'EST LA CAUSE DU BUG FATAL.
    //  */
    // public function show($payment)
    // {
    //     // ... code show()
    // }

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