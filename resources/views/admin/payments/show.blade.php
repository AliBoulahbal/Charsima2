@extends('admin.layouts.admin')
@section('title', 'Détail Paiement')
@section('page-title', 'Paiement #' . $payment->id)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Détails du Paiement</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">ID:</th>
                                <td>#{{ $payment->id }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Montant:</th>
                                <td class="fw-bold text-success">
                                    {{ number_format($payment->amount, 0, ',', ' ') }} DA
                                </td>
                            </tr>
                            <tr>
                                <th>Méthode:</th>
                                <td>
                                    @php
                                        $methodColors = [
                                            'cash' => 'success',
                                            'check' => 'warning', 
                                            'transfer' => 'info',
                                            'other' => 'secondary'
                                        ];
                                        $methodLabels = [
                                            'cash' => 'Espèces',
                                            'check' => 'Chèque',
                                            'transfer' => 'Virement',
                                            'other' => 'Autre'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $methodColors[$payment->method] ?? 'secondary' }}">
                                        {{ $methodLabels[$payment->method] ?? $payment->method }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Note:</th>
                                <td>{{ $payment->note ?? 'Aucune note' }}</td>
                            </tr>
                            <tr>
                                <th>Créé le:</th>
                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Mis à jour:</th>
                                <td>{{ $payment->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Partenaire</h5>
            </div>
            <div class="card-body">
                {{-- CORRECTION DE L'ERREUR : Vérification conditionnelle --}}
                @if($payment->distributor)
                    @php $partner = $payment->distributor; $partnerRoute = 'admin.distributors.show'; @endphp
                    <div class="row">
                        <div class="col-md-8">
                            <h5>{{ $partner->name }} <span class="badge bg-info">Distributeur</span></h5>
                            <p class="mb-1">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $partner->wilaya }}
                            </p>
                            @if($partner->phone)
                            <p class="mb-1">
                                <i class="fas fa-phone me-2"></i>
                                {{ $partner->phone }}
                            </p>
                            @endif
                            @if($partner->user)
                            <p class="mb-0">
                                <i class="fas fa-envelope me-2"></i>
                                {{ $partner->user->email }}
                            </p>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route($partnerRoute, $partner) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt"></i> Voir le distributeur
                            </a>
                        </div>
                    </div>
                @elseif($payment->kiosk)
                    @php $partner = $payment->kiosk; $partnerRoute = 'admin.kiosks.show'; @endphp
                    <div class="row">
                        <div class="col-md-8">
                            <h5>{{ $partner->name }} <span class="badge bg-danger">Kiosque</span></h5>
                            <p class="mb-1">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $partner->wilaya }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-user me-2"></i> Gérant: {{ $partner->user->name ?? $partner->owner_name }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route($partnerRoute, $partner) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-external-link-alt"></i> Voir le kiosque
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-muted">Ce paiement n'est lié ni à un Distributeur ni à un Kiosque (Paiement direct/autre).</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer le Reçu
                    </button>
                    
                    {{-- Actions conditionnelles pour le Distributeur --}}
                    @if($payment->distributor)
                        <a href="{{ route('admin.payments.create') }}?distributor_id={{ $payment->distributor_id }}" 
                        class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau Paiement Distributeur
                        </a>
                        <a href="mailto:{{ $payment->distributor->user->email ?? '#' }}" 
                        class="btn btn-success {{ !$payment->distributor->user ? 'disabled' : '' }}">
                            <i class="fas fa-envelope"></i> Envoyer Reçu
                        </a>
                    @else
                        <a href="{{ route('admin.payments.create') }}" 
                        class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau Paiement
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- CORRECTION: Afficher uniquement si c'est un distributeur --}}
        @if($payment->distributor)
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Situation du Distributeur</h5>
            </div>
            <div class="card-body">
                @php
                    $distributor = $payment->distributor;
                    // Utiliser final_price pour les calculs de solde
                    $totalDelivered = $distributor->deliveries()->sum('final_price') ?? 0;
                    $totalPaid = $distributor->payments()->sum('amount') ?? 0;
                    $remaining = $totalDelivered - $totalPaid;
                @endphp
                <table class="table table-sm">
                    <tr>
                        <td>Montant livré:</td>
                        <td class="text-end">{{ number_format($totalDelivered, 0, ',', ' ') }} DA</td>
                    </tr>
                    <tr>
                        <td>Montant payé:</td>
                        <td class="text-end">{{ number_format($totalPaid, 0, ',', ' ') }} DA</td>
                    </tr>
                    <tr class="{{ $remaining > 0 ? 'table-danger' : 'table-success' }}">
                        <td><strong>Solde restant:</strong></td>
                        <td class="text-end fw-bold">
                            {{ number_format($remaining, 0, ',', ' ') }} DA
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection