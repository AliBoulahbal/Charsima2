@extends('layouts.admin')

@section('title', 'Statistiques des Livraisons')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Statistiques des Livraisons
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Livraisons</span>
                                    <span class="info-box-number">
                                        {{ $monthlyStats->sum('deliveries_count') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-cubes"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cartes Total</span>
                                    <span class="info-box-number">
                                        {{ number_format($monthlyStats->sum('total_cards'), 0, ',', ' ') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Montant Total</span>
                                    <span class="info-box-number">
                                        {{ number_format($monthlyStats->sum('total_amount'), 0, ',', ' ') }} DA
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-danger">
                                <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Wilayas Actives</span>
                                    <span class="info-box-number">{{ $wilayaStats->count() }}</span>
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
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mois/Année</th>
                                                <th>Nombre de Livraisons</th>
                                                <th>Cartes Livrées</th>
                                                <th>Montant Total</th>
                                                <th>Moyenne par Livraison</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($monthlyStats as $month)
                                                @php
                                                    $avg = $month->deliveries_count > 0 ? 
                                                        round($month->total_amount / $month->deliveries_count, 2) : 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}</td>
                                                    <td>{{ $month->deliveries_count }}</td>
                                                    <td>{{ number_format($month->total_cards, 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($month->total_amount, 0, ',', ' ') }} DA</td>
                                                    <td>{{ number_format($avg, 0, ',', ' ') }} DA</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Statistiques par Wilaya</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Wilaya</th>
                                                <th>Livraisons</th>
                                                <th>Cartes</th>
                                                <th>Montant</th>
                                                <th>Part de Marché</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalAmountAll = $wilayaStats->sum('total_amount');
                                            @endphp
                                            @foreach($wilayaStats as $wilaya)
                                                <tr>
                                                    <td>{{ $wilaya->wilaya }}</td>
                                                    <td>{{ $wilaya->deliveries_count }}</td>
                                                    <td>{{ number_format($wilaya->total_cards, 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($wilaya->total_amount, 0, ',', ' ') }} DA</td>
                                                    <td>
                                                        @if($totalAmountAll > 0)
                                                            {{ round(($wilaya->total_amount / $totalAmountAll) * 100, 2) }}%
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
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Top 10 Écoles</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>École</th>
                                                <th>Livraisons</th>
                                                <th>Cartes</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topSchools as $index => $school)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $school->name }}</td>
                                                    <td>{{ $school->deliveries_count }}</td>
                                                    <td>{{ number_format($school->total_cards, 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($school->total_amount, 0, ',', ' ') }} DA</td>
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
                                                <th>Livraisons</th>
                                                <th>Cartes</th>
                                                <th>Montant</th>
                                                <th>Moyenne par Livraison</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topDistributors as $index => $distributor)
                                                @php
                                                    $avg = $distributor->deliveries_count > 0 ? 
                                                        round($distributor->total_amount / $distributor->deliveries_count, 2) : 0;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $distributor->name }}</td>
                                                    <td>{{ $distributor->deliveries_count }}</td>
                                                    <td>{{ number_format($distributor->total_cards, 0, ',', ' ') }}</td>
                                                    <td>{{ number_format($distributor->total_amount, 0, ',', ' ') }} DA</td>
                                                    <td>{{ number_format($avg, 0, ',', ' ') }} DA</td>
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