@extends('admin.layouts.admin')
@section('title', 'Gestion des Distributeurs')
@section('page-title', 'Distributeurs')

@section('page-actions')
    <a href="{{ route('admin.distributors.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau Distributeur
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Rechercher..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <select class="form-select" name="wilaya" onchange="this.form.submit()">
                    <option value="">Toutes les wilayas</option>
                    @foreach($wilayas as $wilaya)
                    <option value="{{ $wilaya }}" {{ request('wilaya') == $wilaya ? 'selected' : '' }}>
                        {{ $wilaya }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            {{-- AJOUT DE L'ID UNIQUE pour le ciblage JS --}}
            <table class="table table-hover datatable" id="distributorsDataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Wilaya</th>
                        <th>Téléphone</th>
                        <th>Livraisons</th>
                        <th>Montant Livré</th>
                        <th>Montant Payé</th>
                        <th>Solde Dû</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributors as $distributor)
                    @php
                        $total_delivered = $distributor->total_delivered ?? 0;
                        $total_paid = $distributor->total_paid ?? 0;
                        $total_due = $total_delivered - $total_paid;
                    @endphp
                    <tr>
                        <td>{{ $distributor->id }}</td>
                        <td>
                            <strong>{{ $distributor->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $distributor->user->email ?? 'Pas de compte' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $distributor->wilaya }}</span>
                        </td>
                        <td>{{ $distributor->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $distributor->deliveries_count > 0 ? 'success' : 'secondary' }}">
                                {{ $distributor->deliveries_count }}
                            </span>
                        </td>
                        <td>
                            <span class="text-primary fw-bold">
                                {{ number_format($total_delivered, 0, ',', ' ') }} DA
                            </span>
                        </td>
                        <td>
                            <span class="text-success fw-bold">
                                {{ number_format($total_paid, 0, ',', ' ') }} DA
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold {{ $total_due > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($total_due, 0, ',', ' ') }} DA
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.distributors.show', $distributor) }}" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.distributors.edit', $distributor) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- LA ROUTE financial-report EST MAINTENANT DÉFINIE DANS web.php --}}
                                <a href="{{ route('admin.distributors.financial-report', $distributor) }}" 
                                   class="btn btn-sm btn-success" title="Rapport financier">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </a>
                                <form action="{{ route('admin.distributors.destroy', $distributor) }}" 
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
                    <tr class="dataTables_empty">
                        <td colspan="9" class="text-center">
                            <div class="py-4 text-muted">
                                <i class="fas fa-truck fa-2x mb-3"></i>
                                <p>Aucun distributeur trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4>{{ $distributors->count() }}</h4>
                        <small>Distributeurs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4>{{ number_format($distributors->sum('total_delivered'), 0, ',', ' ') }} DA</h4>
                        <small>Total Livré</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h4>{{ number_format($distributors->sum('total_paid'), 0, ',', ' ') }} DA</h4>
                        <small>Total Payé</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h4>{{ number_format($distributors->sum('total_delivered') - $distributors->sum('total_paid'), 0, ',', ' ') }} DA</h4>
                        <small>Solde Dû</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $distributors->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var tableSelector = '#distributorsDataTable'; 
        
        // VÉRIFIER L'INSTANCE AVANT INITIALISATION
        if ( ! $.fn.DataTable.isDataTable( tableSelector ) ) {
            $(tableSelector).DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                },
                // --- OPTIONS D'ISOLATION ET DE STABILITÉ ---
                destroy: true, // Force la destruction d'une ancienne instance
                autoWidth: false, // Désactive le calcul automatique des largeurs
                
                // Désactiver les fonctionnalités DataTables en conflit avec la pagination Laravel
                paging: false,
                searching: false,
                ordering: false,
                info: false,
                
                // FORCER LE COMPTE DE 9 COLONNES
                columns: [
                    null, // 1. ID
                    null, // 2. Nom
                    null, // 3. Wilaya
                    null, // 4. Téléphone
                    null, // 5. Livraisons
                    null, // 6. Montant Livré
                    null, // 7. Montant Payé
                    null, // 8. Solde Dû
                    { orderable: false, searchable: false } // 9. Actions
                ]
            });
        }
    });
</script>
@endpush