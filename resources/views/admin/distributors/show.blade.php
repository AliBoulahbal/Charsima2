@extends('admin.layouts.admin')
@section('title', 'Détail Distributeur')
@section('page-title', $distributor->name)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.distributors.edit', $distributor) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('admin.distributors.financial-report', $distributor) }}" class="btn btn-success">
            <i class="fas fa-file-invoice-dollar"></i> Rapport Financier
        </a>
        <a href="{{ route('admin.distributors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Informations distributeur -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Informations du Distributeur</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nom:</th>
                                <td>{{ $distributor->name }}</td>
                            </tr>
                            <tr>
                                <th>Wilaya:</th>
                                <td>
                                    <span class="badge bg-info">{{ $distributor->wilaya }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Téléphone:</th>
                                <td>{{ $distributor->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Compte associé:</th>
                                <td>
                                    @if($distributor->user)
                                    <a href="{{ route('admin.users.show', $distributor->user) }}">
                                        {{ $distributor->user->email }}
                                    </a>
                                    @else
                                    <span class="text-muted">Pas de compte</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Créé le:</th>
                                <td>{{ $distributor->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Mis à jour:</th>
                                <td>{{ $distributor->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Statut:</th>
                                <td>
                                    <span class="badge bg-success">Actif</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card bg-light p-3 rounded">
                            <h3 class="text-primary">{{ $stats['deliveries_count'] }}</h3>
                            <small class="text-muted">Livraisons</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card bg-light p-3 rounded">
                            <h3 class="text-success">{{ number_format($stats['total_delivered'], 0, ',', ' ') }} DA</h3>
                            <small class="text-muted">Montant livré</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card bg-light p-3 rounded">
                            <h3 class="text-warning">{{ number_format($stats['total_paid'], 0, ',', ' ') }} DA</h3>
                            <small class="text-muted">Montant payé</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card bg-light p-3 rounded">
                            <h3 class="{{ $stats['remaining'] > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($stats['remaining'], 0, ',', ' ') }} DA
                            </h3>
                            <small class="text-muted">Solde restant</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières livraisons -->
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i> Dernières Livraisons</h5>
            </div>
            <div class="card-body">
                @if($recentDeliveries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>École</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDeliveries as $delivery)
                            <tr>
                                <td>{{ $delivery->delivery_date->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.schools.show', $delivery->school) }}">
                                        {{ $delivery->school->name }}
                                    </a>
                                </td>
                                <td>{{ number_format($delivery->quantity) }}</td>
                                <td>{{ number_format($delivery->unit_price, 0, ',', ' ') }} DA</td>
                                <td class="fw-bold">{{ number_format($delivery->total_price, 0, ',', ' ') }} DA</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-box-open fa-2x mb-3"></i>
                    <p>Aucune livraison pour ce distributeur</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Derniers paiements -->
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Derniers Paiements</h5>
            </div>
            <div class="card-body">
                @if($recentPayments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td class="fw-bold text-success">
                                    {{ number_format($payment->amount, 0, ',', ' ') }} DA
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $payment->method }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-money-bill-alt fa-2x mb-3"></i>
                    <p>Aucun paiement pour ce distributeur</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.deliveries.create') }}?distributor_id={{ $distributor->id }}" 
                       class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle Livraison
                    </a>
                    <a href="{{ route('admin.payments.create') }}?distributor_id={{ $distributor->id }}" 
                       class="btn btn-success">
                        <i class="fas fa-money-bill-wave"></i> Nouveau Paiement
                    </a>
                    <button class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer la Fiche
                    </button>
                    @if($distributor->user)
                    <a href="mailto:{{ $distributor->user->email }}" class="btn btn-warning">
                        <i class="fas fa-envelope"></i> Envoyer Email
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Solde -->
        <div class="card shadow">
            <div class="card-header {{ $stats['remaining'] > 0 ? 'bg-danger' : 'bg-success' }} text-white">
                <h5 class="mb-0"><i class="fas fa-balance-scale me-2"></i> Situation Financière</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h3 class="{{ $stats['remaining'] > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($stats['remaining'], 0, ',', ' ') }} DA
                    </h3>
                    <p class="mb-0">
                        @if($stats['remaining'] > 0)
                        <span class="text-danger">Solde dû</span>
                        @else
                        <span class="text-success">Solde créditeur</span>
                        @endif
                    </p>
                    <hr>
                    <small class="text-muted">
                        Montant livré: {{ number_format($stats['total_delivered'], 0, ',', ' ') }} DA<br>
                        Montant payé: {{ number_format($stats['total_paid'], 0, ',', ' ') }} DA
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection