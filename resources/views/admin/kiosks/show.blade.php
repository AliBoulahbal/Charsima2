@extends('admin.layouts.admin')
@section('title', 'Détail Kiosque')
@section('page-title', 'Kiosque: ' . $kiosk->name)

@section('page-actions')
    <a href="{{ route('admin.kiosks.sales', $kiosk) }}" class="btn btn-info">
        <i class="fas fa-file-invoice"></i> Historique des Ventes
    </a>
    <a href="{{ route('admin.kiosks.financial-report', $kiosk) }}" class="btn btn-warning">
        <i class="fas fa-chart-line"></i> Rapport Financier
    </a>
    <a href="{{ route('admin.kiosks.edit', $kiosk) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Modifier
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informations Générales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr><th>Nom:</th><td>{{ $kiosk->name }}</td></tr>
                            <tr><th>Gérant:</th><td>{{ $kiosk->owner_name }}</td></tr>
                            <tr><th>Téléphone:</th><td>{{ $kiosk->phone }}</td></tr>
                            <tr><th>Email:</th><td>{{ $kiosk->email ?? 'N/A' }}</td></tr>
                            <tr><th>Statut:</th>
                                <td>
                                    <span class="badge bg-{{ $kiosk->is_active ? 'success' : 'danger' }}">
                                        {{ $kiosk->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                         <table class="table table-borderless table-sm">
                            <tr><th>Wilaya:</th><td>{{ $kiosk->wilaya }}</td></tr>
                            <tr><th>District:</th><td>{{ $kiosk->district }}</td></tr>
                            <tr><th>Adresse:</th><td>{{ $kiosk->address }}</td></tr>
                            <tr><th>Utilisateur lié:</th>
                                <td>
                                    @if($kiosk->user)
                                        <a href="{{ route('admin.users.show', $kiosk->user) }}">
                                            {{ $kiosk->user->name }}
                                        </a>
                                    @else
                                        Aucun
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i> 10 Dernières Ventes (Kiosque & En ligne)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>École (si applicable)</th>
                                <th class="text-end">Montant Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td>{{ $sale->delivery_date->format('d/m/Y') }}</td>
                                <td>{{ $sale->delivery_type }}</td>
                                <td>{{ number_format($sale->quantity) }}</td>
                                <td>{{ $sale->school->name ?? 'N/A' }}</td>
                                <td class="text-end fw-bold text-success">
                                    {{ number_format($sale->final_price, 0, ',', ' ') }} DA
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucune vente récente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Statistiques Clés</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total des Ventes (Montant)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($stats['total_sales'], 0, ',', ' ') }} DA
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ventes du Mois Actuel</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($stats['monthly_sales'], 0, ',', ' ') }} DA
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Nombre Total de Transactions</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($stats['sales_count']) }}
                    </div>
                </div>
                <hr>
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Moyenne par Transaction</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($stats['average_sale'], 0, ',', ' ') }} DA
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection