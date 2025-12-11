@extends('admin.layouts.admin')
@section('title', 'Gestion des Paiements')
@section('page-title', 'Paiements')

@section('page-actions')
    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau Paiement
    </a>
    <a href="{{ route('admin.payments.export') }}" class="btn btn-success ms-2">
        <i class="fas fa-download"></i> Exporter
    </a>
    <a href="{{ route('admin.payments.financial-report') }}" class="btn btn-info ms-2">
        <i class="fas fa-chart-pie"></i> Rapport Financier
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i> Filtres</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Distributeur</label>
                        <select class="form-select" name="distributor_id">
                            <option value="">Tous les distributeurs</option>
                            @foreach($distributors as $distributor)
                            <option value="{{ $distributor->id }}" 
                                    {{ request('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                {{ $distributor->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date de</label>
                        <input type="date" class="form-control" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date à</label>
                        <input type="date" class="form-control" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Méthode</label>
                        <select class="form-select" name="method">
                            <option value="">Toutes</option>
                            @foreach($methods as $key => $method)
                            <option value="{{ $key }}" 
                                    {{ request('method') == $key ? 'selected' : '' }}>
                                {{ $method }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques des filtres -->
        @if(request()->anyFilled(['distributor_id', 'date_from', 'date_to', 'method']))
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center py-2">
                        <h6 class="mb-0">{{ $stats['total'] }}</h6>
                        <small>Paiements</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center py-2">
                        <h6 class="mb-0">{{ number_format($stats['total_amount'], 0, ',', ' ') }} DA</h6>
                        <small>Montant total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center py-2">
                        <h6 class="mb-0">{{ number_format($stats['total_amount'] / max($stats['total'], 1), 0, ',', ' ') }} DA</h6>
                        <small>Moyenne par paiement</small>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tableau -->
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Distributeur</th>
                        <th>Wilaya</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>
                            {{ $payment->payment_date->format('d/m/Y') }}
                            <br>
                            <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.distributors.show', $payment->distributor) }}">
                                {{ $payment->distributor->name }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $payment->distributor->wilaya }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">
                                {{ number_format($payment->amount, 0, ',', ' ') }} DA
                            </span>
                        </td>
                        <td>
                            @php
                                $methodColors = [
                                    'cash' => 'success',
                                    'check' => 'warning', 
                                    'transfer' => 'info',
                                    'other' => 'secondary'
                                ];
                            @endphp
                            <span class="badge bg-{{ $methodColors[$payment->method] ?? 'secondary' }}">
                                {{ $methods[$payment->method] ?? $payment->method }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.payments.show', $payment) }}" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.payments.edit', $payment) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.payments.destroy', $payment) }}" 
                                      method="POST" class="d-inline" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="py-4 text-muted">
                                <i class="fas fa-money-bill-wave fa-2x mb-3"></i>
                                <p>Aucun paiement trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection


<table class="table table-hover datatable" id="paymentsDataTable">

</table>


@push('scripts')
<script>
    $(document).ready(function() {
        var tableSelector = '#paymentsDataTable'; 
        
        if ( ! $.fn.DataTable.isDataTable( tableSelector ) ) {
            $(tableSelector).DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
                paging: false, searching: false, ordering: false, info: false,
                // Définition de 7 colonnes:
                columns: [null, null, null, null, null, null, { orderable: false, searchable: false }]
            });
        }
    });
</script>
@endpush