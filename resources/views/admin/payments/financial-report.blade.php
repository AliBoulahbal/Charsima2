@extends('admin.layouts.admin')
@section('title', 'Rapport Financier Complet')
@section('page-title', 'Rapport Financier des Transactions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-balance-scale me-2"></i> Vue d'ensemble Financière (Globale)</h5>
                </div>
                <div class="card-body">
                    
                    <div class="row mb-4">
                        {{-- 1. Total Paiements --}}
                        <div class="col-md-4">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Paiements Reçus</span>
                                    <span class="info-box-number">
                                        {{ number_format($totalPayments, 0, ',', ' ') }} DA
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- 2. Total Revenu (Livraisons) --}}
                        <div class="col-md-4">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-truck-loading"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Montant Livré (Revenu Brut)</span>
                                    <span class="info-box-number">
                                        {{ number_format($totalRevenue, 0, ',', ' ') }} DA
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- 3. Flux Net (Simplifié) --}}
                        <div class="col-md-4">
                            @php $netFlowColor = $netCashFlow >= 0 ? 'bg-gradient-success' : 'bg-gradient-danger'; @endphp
                            <div class="info-box {{ $netFlowColor }}">
                                <span class="info-box-icon"><i class="fas fa-exchange-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Flux de Trésorerie Net (Paiements - Revenu)</span>
                                    <span class="info-box-number">
                                        {{ number_format($netCashFlow, 0, ',', ' ') }} DA
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Breakdown Paiements --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="fas fa-list-alt me-2"></i> Détail des Paiements par Source</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $paymentTypes = [
                                        'distributor' => ['label' => 'Distributeur', 'color' => 'primary', 'amount' => $distributorPayments],
                                        'kiosk' => ['label' => 'Kiosque', 'color' => 'warning', 'amount' => $kioskPayments],
                                        'online' => ['label' => 'Vente en Ligne', 'color' => 'success', 'amount' => $onlinePayments],
                                        'other' => ['label' => 'Autre / Interne', 'color' => 'secondary', 'amount' => $otherPayments],
                                    ];
                                @endphp
                                @foreach($paymentTypes as $type)
                                <div class="col-md-3">
                                    <div class="small-box bg-{{ $type['color'] }}">
                                        <div class="inner">
                                            <h3>{{ number_format($type['amount'], 0, ',', ' ') }} DA</h3>
                                            <p>{{ $type['label'] }}</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    {{-- Section: Tableau Solde Distributeurs --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i> Solde Détaillé des Distributeurs</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom du Distributeur</th>
                                            <th class="text-end">Wilaya</th>
                                            <th class="text-end">Montant Livré (Dû)</th>
                                            <th class="text-end">Montant Payé</th>
                                            <th class="text-end">Solde Dû</th>
                                            <th class="text-center">Taux de Paiement</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributorBalances as $distributor)
                                            @php
                                                $delivered = $distributor->total_delivered;
                                                $paid = $distributor->total_paid;
                                                $balance = $delivered - $paid;
                                                $paymentRate = $delivered > 0 ? round(($paid / $delivered) * 100) : 0;
                                                // La couleur dépend si le solde est dû par le partenaire (balance > 0) ou si le partenaire a trop payé (balance < 0)
                                                $balanceClass = $balance > 0 ? 'text-danger' : ($balance < 0 ? 'text-success' : 'text-secondary');
                                            @endphp
                                            <tr>
                                                <td>{{ $distributor->name }}</td>
                                                <td class="text-end">{{ $distributor->wilaya }}</td>
                                                <td class="text-end">{{ number_format($delivered, 0, ',', ' ') }} DA</td>
                                                <td class="text-end">{{ number_format($paid, 0, ',', ' ') }} DA</td>
                                                <td class="{{ $balanceClass }} fw-bold">
                                                    {{ number_format($balance, 0, ',', ' ') }} DA
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 18px;">
                                                        <div class="progress-bar {{ $paymentRate >= 100 ? 'bg-success' : ($paymentRate >= 75 ? 'bg-info' : 'bg-warning') }}" 
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
                    
                    {{-- Section: Tableau Solde Kiosques --}}
                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0"><i class="fas fa-store me-2"></i> Solde Détaillé des Kiosques</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom du Kiosque</th>
                                            <th class="text-end">Wilaya</th>
                                            <th class="text-end">Montant Livré (Dû)</th>
                                            <th class="text-end">Montant Payé</th>
                                            <th class="text-end">Solde Dû</th>
                                            <th class="text-center">Taux de Paiement</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kioskBalances as $kiosk)
                                            @php
                                                $delivered = $kiosk->total_delivered;
                                                $paid = $kiosk->total_paid;
                                                $balance = $delivered - $paid;
                                                $paymentRate = $delivered > 0 ? round(($paid / $delivered) * 100) : 0;
                                                // La couleur dépend si le solde est dû par le partenaire (balance > 0) ou si le partenaire a trop payé (balance < 0)
                                                $balanceClass = $balance > 0 ? 'text-danger' : ($balance < 0 ? 'text-success' : 'text-secondary');
                                            @endphp
                                            <tr>
                                                <td>{{ $kiosk->name }}</td>
                                                <td class="text-end">{{ $kiosk->wilaya }}</td>
                                                <td class="text-end">{{ number_format($delivered, 0, ',', ' ') }} DA</td>
                                                <td class="text-end">{{ number_format($paid, 0, ',', ' ') }} DA</td>
                                                <td class="{{ $balanceClass }} fw-bold">
                                                    {{ number_format($balance, 0, ',', ' ') }} DA
                                                </td>
                                                <td>
                                                    <div class="progress" style="height: 18px;">
                                                        <div class="progress-bar {{ $paymentRate >= 100 ? 'bg-success' : ($paymentRate >= 75 ? 'bg-info' : 'bg-warning') }}" 
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
@endsection