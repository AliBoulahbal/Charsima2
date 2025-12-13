@extends('admin.layouts.admin')
@section('title', 'Détail Livraison ' . $delivery->transaction_id)
@section('page-title', 'Livraison #' . $delivery->transaction_id)

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
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informations Générales</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th width="30%">ID Transaction:</th>
                        <td>{{ $delivery->transaction_id }}</td>
                    </tr>
                    <tr>
                        <th>Date de Livraison:</th>
                        <td>{{ $delivery->delivery_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Type de Livraison:</th>
                        <td><span class="badge bg-info">{{ $delivery->delivery_type_formatted }}</span></td>
                    </tr>
                    <tr>
                        <th>Statut:</th>
                        <td><span class="badge bg-success">{{ $delivery->status }}</span></td>
                    </tr>
                    <tr>
                        <th>Montant total (Net):</th>
                        <td><h4 class="text-success">{{ number_format($delivery->final_price, 0, ',', ' ') }} DA</h4></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-handshake me-2"></i> Partenaire & Localisation</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Colonne GAUCHE: Informations sur le Partenaire --}}
                    <div class="col-md-6">
                        <h6>Partenaire:</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>Type:</th>
                                <td>
                                    @if($delivery->distributor)
                                        Distributeur
                                    @elseif($delivery->kiosk)
                                        Kiosque
                                    @else
                                        Vente Directe / Enseignant
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Nom:</th>
                                <td>
                                    @if($delivery->distributor)
                                        <a href="{{ route('admin.distributors.show', $delivery->distributor) }}">{{ $delivery->distributor->user->name ?? $delivery->distributor->name }}</a>
                                    @elseif($delivery->kiosk)
                                        <a href="{{ route('admin.kiosks.show', $delivery->kiosk) }}">{{ $delivery->kiosk->name }}</a>
                                    @else
                                        {{ $delivery->teacher_name ?? 'N/A' }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Colonne DROITE: Informations sur l'École/Localisation --}}
                    <div class="col-md-6">
                        <h6>Localisation:</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>École:</th>
                                <td>
                                    {{-- CORRECTION DE L'ERREUR DE LA LIGNE 78 --}}
                                    @if($delivery->school)
                                        <a href="{{ route('admin.schools.show', $delivery->school) }}">{{ $delivery->school->name }}</a>
                                        <br><small class="text-muted">{{ $delivery->school->wilaya }}</small>
                                    @else
                                        <span class="text-muted">N/A ({{ $delivery->delivery_type_formatted }})</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Wilaya (Livraison):</th>
                                <td>{{ $delivery->wilaya ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Adresse client:</th>
                                <td>{{ $delivery->delivery_address ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Montants</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Quantité (Unités):</th>
                        <td>{{ number_format($delivery->quantity) }}</td>
                    </tr>
                    <tr>
                        <th>Prix Unitaire:</th>
                        <td>{{ number_format($delivery->unit_price, 0, ',', ' ') }} DA</td>
                    </tr>
                    <tr>
                        <th>Total Brut:</th>
                        <td>{{ number_format($delivery->total_price, 0, ',', ' ') }} DA</td>
                    </tr>
                    @if($delivery->discount_percentage > 0)
                    <tr>
                        <th class="text-danger">Remise:</th>
                        <td class="text-danger">{{ $delivery->discount_percentage }} %</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="fw-bold">Montant Final:</th>
                        <td class="fw-bold text-success">{{ number_format($delivery->final_price, 0, ',', ' ') }} DA</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-user-tag me-2"></i> Client</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Nom Enseignant/Client:</th>
                        <td>{{ $delivery->teacher_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Téléphone:</th>
                        <td>{{ $delivery->teacher_phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $delivery->teacher_email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>CIN/Pièce d'identité:</th>
                        <td>{{ $delivery->customer_cin ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Matière:</th>
                        <td>{{ $delivery->teacher_subject ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection