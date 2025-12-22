@extends('admin.layouts.admin')
@section('title', 'Gestion des Distributeurs')
@section('page-title', 'Distributeurs')

@section('page-actions')
    <a href="{{ route('admin.distributors.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> Nouveau Distributeur
    </a>
@endsection

@section('content')
<div class="card shadow border-0">
    <div class="card-body">
        
        {{-- SECTION FILTRES --}}
        <div class="row mb-4">
            <div class="col-md-5">
                <form method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher un nom ou téléphone..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form method="GET" id="filterForm">
                    <select class="form-select" name="wilaya" onchange="this.form.submit()">
                        <option value="">Toutes les wilayas</option>
                        @foreach($wilayas as $wilaya)
                        <option value="{{ $wilaya }}" {{ request('wilaya') == $wilaya ? 'selected' : '' }}>
                            {{ $wilaya }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        {{-- TABLEAU DES DISTRIBUTEURS --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="distributorsDataTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom du Distributeur</th>
                        <th>Wilaya</th>
                        <th class="text-center">Bons</th>
                        <th class="text-center">Cartes Reçues</th> {{-- Stock envoyé par l'admin --}}
                        <th class="text-center">Cartes Livrées</th> {{-- Ventes aux écoles --}}
                        <th class="text-center">Disponible</th>    {{-- Stock en main --}}
                        <th>Solde Dû (DA)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributors as $distributor)
                    @php
                        // Calcul financier
                        $total_delivered_money = $distributor->total_delivered_money ?? 0;
                        $total_paid_money = $distributor->total_paid_money ?? 0;
                        $total_due = $total_delivered_money - $total_paid_money;
                        
                        // Calcul du stock : Ce qui est reçu par l'admin moins ce qui est livré aux écoles
                        $disponible = ($distributor->total_received ?? 0) - ($distributor->cards_delivered ?? 0);
                    @endphp
                    <tr>
                        <td>{{ $distributor->id }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $distributor->name }}</div>
                            <small class="text-muted"><i class="fas fa-phone me-1"></i> {{ $distributor->phone ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $distributor->wilaya }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill">{{ $distributor->deliveries_count }}</span>
                        </td>
                        
                        {{-- Indicateurs de Stock --}}
                        <td class="text-center fw-bold">
                            {{ number_format($distributor->total_received ?? 0, 0, ',', ' ') }}
                        </td>
                        <td class="text-center fw-bold text-success">
                            {{ number_format($distributor->cards_delivered ?? 0, 0, ',', ' ') }}
                        </td>
                        <td class="text-center">
                            @if($disponible > 0)
                                <span class="badge bg-primary fs-6">{{ number_format($disponible, 0, ',', ' ') }}</span>
                            @elseif($disponible < 0)
                                <span class="badge bg-danger fs-6">{{ number_format($disponible, 0, ',', ' ') }}</span>
                            @else
                                <span class="badge bg-warning text-dark fs-6">0</span>
                            @endif
                        </td>

                        {{-- Situation Financière --}}
                        <td>
                            <span class="fw-bold {{ $total_due > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($total_due, 0, ',', ' ') }}
                            </span>
                        </td>

                        <td class="text-end">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('admin.distributors.show', $distributor) }}" class="btn btn-sm btn-outline-info" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.distributors.financial-report', $distributor) }}" class="btn btn-sm btn-outline-success" title="Rapport financier">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </a>
                                <a href="{{ route('admin.distributors.edit', $distributor) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.distributors.destroy', $distributor) }}" method="POST" class="d-inline" onsubmit="return confirm('Attention : Toutes les données liées à ce distributeur seront supprimées. Confirmer ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-info-circle fa-2x mb-3"></i><br>
                            Aucun distributeur trouvé pour vos critères.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Laravel --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $distributors->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialisation DataTables si nécessaire
        if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#distributorsDataTable')) {
            $('#distributorsDataTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                columnDefs: [
                    { targets: [8], orderable: false } // Désactiver tri sur colonne Actions
                ]
            });
        }
    });
</script>
@endpush