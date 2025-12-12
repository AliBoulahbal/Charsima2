@extends('admin.layouts.admin')
@section('content')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Dashboard Admin</h1>
        <div>
            <span class="badge bg-primary">Total Distributeurs: {{ $distributorCount }}</span>
            <span class="badge bg-success ms-2">Total Écoles: {{ $schoolCount }}</span>
            {{-- AJOUT DU BADGE POUR LES KIOSQUES --}}
            <span class="badge bg-danger ms-2">Total Kiosques: {{ $kioskCount ?? 0 }}</span> 
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Cartes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalCards) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Montant attendu
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalExpected, 0, ',', ' ') }} DA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Montant payé
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalPaid, 0, ',', ' ') }} DA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Reste à payer
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($remaining, 0, ',', ' ') }} DA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Distributeurs</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Wilaya</th>
                                    <th>Livraisons</th>
                                    <th>Montant Livré</th>
                                    <th>Solde Dû</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topDistributors as $dist)
                                <tr>
                                    <td>{{ $dist->user->name ?? $dist->name }}</td>
                                    <td>{{ $dist->wilaya }}</td>
                                    <td>{{ $dist->deliveries_count }}</td>
                                    <td>{{ number_format($dist->total_delivered, 0, ',', ' ') }} DA</td>
                                    <td class="{{ $dist->total_due > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($dist->total_due, 0, ',', ' ') }} DA
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dernières Livraisons</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>École / Type</th>
                                    <th>Partenaire</th>
                                    <th>Quantité</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDeliveries as $delivery)
                                <tr>
                                    <td>{{ $delivery->delivery_date->format('d/m/Y') }}</td>
                                    
                                    {{-- CORRECTION 1: Rendre l'accès à l'école conditionnel (Résout l'erreur) --}}
                                    <td>
                                        @if($delivery->school)
                                            {{ $delivery->school->name }}
                                        @else
                                            {{-- Utilisation de l'accessor formaté --}}
                                            <span class="badge bg-secondary">{{ $delivery->delivery_type_formatted ?? $delivery->delivery_type }}</span>
                                        @endif
                                    </td>
                                    
                                    {{-- CORRECTION 2: Afficher Distributeur, Kiosque ou Client --}}
                                    <td>
                                        @if($delivery->distributor)
                                            {{ $delivery->distributor->user->name ?? $delivery->distributor->name }} (Dist.)
                                        @elseif($delivery->kiosk)
                                            {{ $delivery->kiosk->name }} (Kiosque)
                                        @elseif($delivery->teacher_name)
                                            {{ $delivery->teacher_name }} (Client)
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ number_format($delivery->quantity) }}</td>
                                    {{-- CORRECTION 3: Utiliser final_price (Montant après remise) --}}
                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ number_format($delivery->final_price, 0, ',', ' ') }} DA
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Écoles</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Livraisons</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSchools as $school)
                                <tr>
                                    <td>{{ $school->name }}</td>
                                    <td>{{ $school->deliveries_count }}</td>
                                    <td>{{ number_format($school->total_delivered, 0, ',', ' ') }} DA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distributeurs par Wilaya</h6>
                </div>
                <div class="card-body">
                    @foreach($wilayaStats as $wilaya)
                    <div class="mb-3">
                        <div class="small text-gray-500">{{ $wilaya->wilaya }}</div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-info" role="progressbar" 
                                 style="width: {{ ($wilaya->distributor_count / $distributorCount) * 100 }}%">
                                {{ $wilaya->distributor_count }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .progress {
        border-radius: 10px;
    }
    .progress-bar {
        border-radius: 10px;
    }
    .table th {
        background-color: #f8f9fc;
    }
</style>
@endpush