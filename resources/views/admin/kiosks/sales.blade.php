@extends('admin.layouts.admin')
@section('title', 'Ventes Kiosque')
@section('page-title', 'Historique des Ventes: ' . $kiosk->name)

@section('page-actions')
    <a href="{{ route('admin.kiosks.show', $kiosk) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour au Kiosque
    </a>
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i> Filtres de Ventes</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Type de Vente</label>
                <select class="form-select" name="type">
                    <option value="">Tous les types</option>
                    <option value="kiosk" {{ request('type') == 'kiosk' ? 'selected' : '' }}>Vente kiosque</option>
                    <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>Vente en ligne</option>
                    <option value="teacher_free" {{ request('type') == 'teacher_free' ? 'selected' : '' }}>Carte enseignant gratuite</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date de</label>
                <input type="date" class="form-control" name="date_from" 
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date à</label>
                <input type="date" class="form-control" name="date_to" 
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.kiosks.sales', $kiosk) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Remise (%)</th>
                        <th>Montant Final</th>
                        <th>École / Client</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->delivery_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $sale->delivery_type == 'kiosk' ? 'primary' : 
                                ($sale->delivery_type == 'online' ? 'info' : 'warning') 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $sale->delivery_type)) }}
                            </span>
                        </td>
                        <td>{{ number_format($sale->quantity) }}</td>
                        <td>{{ number_format($sale->unit_price, 0, ',', ' ') }} DA</td>
                        <td>{{ number_format($sale->discount_percentage) }} %</td>
                        <td class="fw-bold text-success">
                            {{ number_format($sale->final_price, 0, ',', ' ') }} DA
                        </td>
                        <td>
                            @if($sale->school)
                                {{ $sale->school->name }} (École)
                            @elseif($sale->teacher_name)
                                {{ $sale->teacher_name }} (Client)
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.deliveries.show', $sale) }}" 
                               class="btn btn-sm btn-info" title="Voir Détails">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="py-4 text-muted">
                                <i class="fas fa-file-invoice-slash fa-2x mb-3"></i>
                                <p>Aucune vente trouvée pour ce kiosque.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection