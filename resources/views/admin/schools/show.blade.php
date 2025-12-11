@extends('admin.layouts.admin')
@section('title', 'Détail École')
@section('page-title', $school->name)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('admin.schools.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Informations école -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-school me-2"></i> Informations de l'École</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nom:</th>
                                <td>{{ $school->name }}</td>
                            </tr>
                            <tr>
                                <th>Wilaya:</th>
                                <td>
                                    <span class="badge bg-info">{{ $school->wilaya }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>District:</th>
                                <td>{{ $school->district }}</td>
                            </tr>
                            <tr>
                                <th>Téléphone:</th>
                                <td>{{ $school->phone ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Directeur:</th>
                                <td>{{ $school->manager_name }}</td>
                            </tr>
                            <tr>
                                <th>Nombre d'élèves:</th>
                                <td>{{ number_format($school->student_count) }}</td>
                            </tr>
                            <tr>
                                <th>Créé le:</th>
                                <td>{{ $school->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Mis à jour:</th>
                                <td>{{ $school->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Statistiques des Livraisons</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card bg-light p-3 rounded">
                            <h3 class="text-primary">{{ $stats['total_deliveries'] }}</h3>
                            <small class="text-muted">Livraisons</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card bg-light p-3 rounded">
                            <h3 class="text-success">{{ number_format($stats['total_cards']) }}</h3>
                            <small class="text-muted">Cartes livrées</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card bg-light p-3 rounded">
                            <h3 class="text-warning">{{ number_format($stats['total_amount'], 0, ',', ' ') }} DA</h3>
                            <small class="text-muted">Montant total</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card bg-light p-3 rounded">
                            <h3 class="text-danger">{{ number_format($stats['total_paid'], 0, ',', ' ') }} DA</h3>
                            <small class="text-muted">Montant payé</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières livraisons -->
        <div class="card shadow">
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
                                <th>Distributeur</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDeliveries as $delivery)
                            <tr>
                                <td>{{ $delivery->delivery_date->format('d/m/Y') }}</td>
                                <td>{{ $delivery->distributor->user->name ?? $delivery->distributor->name }}</td>
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
                    <p>Aucune livraison pour cette école</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Carte de localisation (exemple) -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Localisation</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                    <p class="mb-2"><strong>Wilaya:</strong> {{ $school->wilaya }}</p>
                    <p class="mb-0"><strong>District:</strong> {{ $school->district }}</p>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.deliveries.create') }}?school_id={{ $school->id }}" 
                       class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle Livraison
                    </a>
                    <button class="btn btn-success" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer la Fiche
                    </button>
                    <a href="mailto:{{ $school->email ?? '#' }}" 
                       class="btn btn-info {{ !$school->email ? 'disabled' : '' }}">
                        <i class="fas fa-envelope"></i> Envoyer Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection