@extends('layouts.admin')

@section('title', 'Rapport Financier du Distributeur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> Rapport Financier - {{ $distributor->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.distributors.show', $distributor) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-truck"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Livré</span>
                                    <span class="info-box-number">
                                        {{ number_format($distributor->deliveries->sum('total_price'), 0, ',', ' ') }} DA
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Payé</span>
                                    <span class="info-box-number">
                                        {{ number_format($distributor->payments->sum('amount'), 0, ',', ' ') }} DA
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-balance-scale"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Solde</span>
                                    @php
                                        $balance = $distributor->payments->sum('amount') - $distributor->deliveries->sum('total_price');
                                    @endphp
                                    <span class="info-box-number {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($balance, 0, ',', ' ') }} DA
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Évolution Mensuelle</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Mois/Année</th>
                                                    <th>Livraisons</th>
                                                    <th>Cartes Livrées</th>
                                                    <th>Montant Livré</th>
                                                    <th>Paiements Reçus</th>
                                                    <th>Solde Mensuel</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    // Combiner les données des livraisons et paiements par mois
                                                    $months = [];
                                                    foreach($monthlyDeliveries as $delivery) {
                                                        $key = $delivery->year . '-' . str_pad($delivery->month, 2, '0', STR_PAD_LEFT);
                                                        $months[$key] = [
                                                            'year' => $delivery->year,
                                                            'month' => $delivery->month,
                                                            'deliveries_count' => $delivery->deliveries_count,
                                                            'total_cards' => $delivery->total_cards,
                                                            'total_delivered' => $delivery->total_amount,
                                                            'total_paid' => 0
                                                        ];
                                                    }
                                                    foreach($monthlyPayments as $payment) {
                                                        $key = $payment->year . '-' . str_pad($payment->month, 2, '0', STR_PAD_LEFT);
                                                        if(isset($months[$key])) {
                                                            $months[$key]['total_paid'] = $payment->total_paid;
                                                        } else {
                                                            $months[$key] = [
                                                                'year' => $payment->year,
                                                                'month' => $payment->month,
                                                                'deliveries_count' => 0,
                                                                'total_cards' => 0,
                                                                'total_delivered' => 0,
                                                                'total_paid' => $payment->total_paid
                                                            ];
                                                        }
                                                    }
                                                    krsort($months); // Trier par date décroissante
                                                @endphp
                                                @foreach($months as $month)
                                                    @php
                                                        $monthlyBalance = $month['total_paid'] - $month['total_delivered'];
                                                    @endphp
                                                    <tr>
                                                        <td>{{ DateTime::createFromFormat('!m', $month['month'])->format('F') }} {{ $month['year'] }}</td>
                                                        <td>{{ $month['deliveries_count'] }}</td>
                                                        <td>{{ number_format($month['total_cards'], 0, ',', ' ') }}</td>
                                                        <td>{{ number_format($month['total_delivered'], 0, ',', ' ') }} DA</td>
                                                        <td>{{ number_format($month['total_paid'], 0, ',', ' ') }} DA</td>
                                                        <td class="{{ $monthlyBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ number_format($monthlyBalance, 0, ',', ' ') }} DA
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Dernières Livraisons (Top 10)</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>École</th>
                                                <th>Cartes</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($distributor->deliveries->sortByDesc('delivery_date')->take(10) as $delivery)
                                                <tr>
                                                    <td>{{ $delivery->delivery_date->format('d/m/Y') }}</td>
                                                    <td>{{ $delivery->school->name ?? 'N/A' }}</td>
                                                    <td>{{ number_format($delivery->quantity, 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($delivery->total_price, 0, ',', ' ') }} DA</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Derniers Paiements (Top 10)</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Méthode</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($distributor->payments->sortByDesc('payment_date')->take(10) as $payment)
                                                <tr>
                                                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                                    <td>
                                                        @switch($payment->method)
                                                            @case('cash') Espèces @break
                                                            @case('check') Chèque @break
                                                            @case('transfer') Virement @break
                                                            @default Autre
                                                        @endswitch
                                                    </td>
                                                    <td>{{ number_format($payment->amount, 0, ',', ' ') }} DA</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Statistiques par École</h3>
                                </div>
                                <div class="card-body">
                                    @php
                                        $schoolStats = $distributor->deliveries->groupBy('school_id')->map(function($deliveries, $schoolId) {
                                            $school = $deliveries->first()->school ?? null;
                                            return [
                                                'school_name' => $school ? $school->name : 'École Inconnue',
                                                'deliveries_count' => $deliveries->count(),
                                                'total_cards' => $deliveries->sum('quantity'),
                                                'total_amount' => $deliveries->sum('total_price'),
                                                'last_delivery' => $deliveries->sortByDesc('delivery_date')->first()->delivery_date ?? null
                                            ];
                                        })->sortByDesc('total_amount');
                                    @endphp
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>École</th>
                                                <th>Livraisons</th>
                                                <th>Cartes Total</th>
                                                <th>Montant Total</th>
                                                <th>Dernière Livraison</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($schoolStats as $stat)
                                                <tr>
                                                    <td>{{ $stat['school_name'] }}</td>
                                                    <td>{{ $stat['deliveries_count'] }}</td>
                                                    <td>{{ number_format($stat['total_cards'], 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($stat['total_amount'], 0, ',', ' ') }} DA</td>
                                                    <td>{{ $stat['last_delivery'] ? $stat['last_delivery']->format('d/m/Y') : 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection