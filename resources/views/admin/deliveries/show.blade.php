@extends('admin.layouts.admin')
@section('title', 'Détail Livraison')
@section('page-title', 'Livraison #' . $delivery->id)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.deliveries.edit', $delivery) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('admin.deliveries.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Informations livraison -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i> Détails de la Livraison</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">ID:</th>
                                <td>#{{ $delivery->id }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $delivery->delivery_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Quantité:</th>
                                <td>
                                    <span class="badge bg-secondary">{{ number_format($delivery->quantity) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Prix unitaire:</th>
                                <td>{{ number_format($delivery->unit_price, 0, ',', ' ') }} DA</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Montant total:</th>
                                <td class="fw-bold text-success">
                                    {{ number_format($delivery->total_price, 0, ',', ' ') }} DA
                                </td>
                            </tr>
                            <tr>
                                <th>Créé le:</th>
                                <td>{{ $delivery->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Mis à jour:</th>
                                <td>{{ $delivery->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations école -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-school me-2"></i> École</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ $delivery->school->name }}</h5>
                        <p class="mb-1">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $delivery->school->district }}, {{ $delivery->school->wilaya }}
                        </p>
                        @if($delivery->school->phone)
                        <p class="mb-1">
                            <i class="fas fa-phone me-2"></i>
                            {{ $delivery->school->phone }}
                        </p>
                        @endif
                        <p class="mb-0">
                            <i class="fas fa-user-tie me-2"></i>
                            {{ $delivery->school->manager_name }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('admin.schools.show', $delivery->school) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-external-link-alt"></i> Voir l'école
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations distributeur -->
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Distributeur</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ $delivery->distributor->name }}</h5>
                        <p class="mb-1">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $delivery->distributor->wilaya }}
                        </p>
                        @if($delivery->distributor->phone)
                        <p class="mb-1">
                            <i class="fas fa-phone me-2"></i>
                            {{ $delivery->distributor->phone }}
                        </p>
                        @endif
                        @if($delivery->distributor->user)
                        <p class="mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            {{ $delivery->distributor->user->email }}
                        </p>
                        @endif
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('admin.distributors.show', $delivery->distributor) }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-external-link-alt"></i> Voir le distributeur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.payments.create-for-delivery', $delivery) }}" 
                       class="btn btn-success">
                        <i class="fas fa-money-bill-wave"></i> Enregistrer un Paiement
                    </a>
                    <button class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer le Bon
                    </button>
                    <a href="{{ route('admin.deliveries.create') }}?school_id={{ $delivery->school_id }}&distributor_id={{ $delivery->distributor_id }}" 
                       class="btn btn-primary">
                        <i class="fas fa-copy"></i> Dupliquer la Livraison
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i> Calculs</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Quantité:</td>
                        <td class="text-end">{{ number_format($delivery->quantity) }}</td>
                    </tr>
                    <tr>
                        <td>Prix unitaire:</td>
                        <td class="text-end">{{ number_format($delivery->unit_price, 0, ',', ' ') }} DA</td>
                    </tr>
                    <tr class="table-primary">
                        <td><strong>Total:</strong></td>
                        <td class="text-end fw-bold">
                            {{ number_format($delivery->total_price, 0, ',', ' ') }} DA
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection