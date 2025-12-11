@extends('layouts.admin')

@section('title', 'Rapport Financier')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-line"></i> Rapport Financier des Paiements
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Paiements</span>
                                    <span class="info-box-number">
                                        {{ $monthlyPayments->sum('total_amount') ? number_format($monthlyPayments->sum('total_amount'), 0, ',', ' ') : '0' }} DA
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-cash-register"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Moyen Fréquent</span>
                                    <span class="info-box-number">
                                        @php
                                            $topMethod = $methodStats->sortByDesc('total_amount')->first();
                                            echo $topMethod ? ($topMethod->method == 'cash' ? 'Espèces' : 
                                                ($topMethod->method == 'check' ? 'Chèque' : 
                                                ($topMethod->method == 'transfer' ? 'Virement' : 'Autre'))) : 'N/A';
                                        @endphp
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Distributeurs Actifs</span>
                                    <span class="info-box-number">{{ $topDistributors->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-danger">
                                <span class="info-box-icon"><i class="fas fa-chart-bar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Période Analysée</span>
                                    <span class="info-box-number">{{ $monthlyPayments->count() }} mois</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Paiements par Mois</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mois/Année</th>
                                                <th>Nombre</th>
                                                <th>Montant Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($monthlyPayments as $month)
                                                <tr>
                                                    <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}</td>
                                                    <td>{{ $month->payments_count }}</td>
                                                    <td>{{ number_format($month->total_amount, 0, ',', ' ') }} DA</td>
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
                                    <h3 class="card-title">Paiements par Méthode</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Méthode</th>
                                                <th>Nombre</th>
                                                <th>Montant Total</th>
                                                <th>Pourcentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalAmount = $methodStats->sum('total_amount');
                                            @endphp
                                            @foreach($methodStats as $method)
                                                <tr>
                                                    <td>
                                                        @switch($method->method)
                                                            @case('cash') Espèces @break
                                                            @case('check') Chèque @break
                                                            @case('transfer') Virement @break
                                                            @default Autre
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $method->payments_count }}</td>
                                                    <td>{{ number_format($method->total_amount, 0, ',', ' ') }} DA</td>
                                                    <td>
                                                        @if($totalAmount > 0)
                                                            {{ round(($method->total_amount / $totalAmount) * 100, 2) }}%
                                                        @else
                                                            0%
                                                        @endif
                                                    </td>
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
                                    <h3 class="card-title">Top 10 Distributeurs</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Distributeur</th>
                                                <th>Nombre de Paiements</th>
                                                <th>Total Payé</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topDistributors as $index => $distributor)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $distributor->name }}</td>
                                                    <td>{{ $distributor->payments_count }}</td>
                                                    <td>{{ number_format($distributor->total_paid, 0, ',', ' ') }} DA</td>
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
                                    <h3 class="card-title">Comparaison Livraisons vs Paiements</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Distributeur</th>
                                                <th>Total Livré</th>
                                                <th>Total Payé</th>
                                                <th>Solde</th>
                                                <th>Taux de Paiement</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($comparisonStats as $distributor)
                                                @php
                                                    $balance = $distributor->total_paid - $distributor->total_delivered;
                                                    $paymentRate = $distributor->total_delivered > 0 ? 
                                                        round(($distributor->total_paid / $distributor->total_delivered) * 100, 2) : 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ $distributor->name }}</td>
                                                    <td>{{ number_format($distributor->total_delivered, 0, ',', ' ') }} DA</td>
                                                    <td>{{ number_format($distributor->total_paid, 0, ',', ' ') }} DA</td>
                                                    <td class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($balance, 0, ',', ' ') }} DA
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar {{ $paymentRate >= 100 ? 'bg-success' : ($paymentRate >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                                 style="width: {{ min($paymentRate, 100) }}%">
                                                                {{ $paymentRate }}%
                                                            </div>
                                                        </div>
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
            </div>
        </div>
    </div>
</div>
@endsection