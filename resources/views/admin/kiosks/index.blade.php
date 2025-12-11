@extends('admin.layouts.admin')
@section('title', 'Gestion des Kiosques')
@section('page-title', 'Kiosques & Points de Vente')

@section('page-actions')
    <a href="{{ route('admin.kiosks.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau Kiosque
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i> Filtres</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Wilaya</label>
                        <select class="form-select" name="wilaya">
                            <option value="">Toutes les Wilayas</option>
                            @foreach($wilayas as $wilaya)
                            <option value="{{ $wilaya }}" 
                                    {{ request('wilaya') == $wilaya ? 'selected' : '' }}>
                                {{ $wilaya }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="status">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Recherche</label>
                        <input type="text" class="form-control" name="search" 
                               placeholder="Nom, Gérant, Téléphone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.kiosks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover datatable" id="kiosksDataTable">
                <thead>
                    <tr>
                        <th>Nom du Kiosque</th>
                        <th>Gérant</th>
                        <th>Wilaya</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Ventes (Qty)</th>
                        <th class="text-end">Ventes (Montant)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kiosks as $kiosk)
                    <tr>
                        <td>
                            <a href="{{ route('admin.kiosks.show', $kiosk) }}">
                                {{ $kiosk->name }}
                            </a>
                            <br><small class="text-muted">{{ $kiosk->phone }}</small>
                        </td>
                        <td>{{ $kiosk->owner_name }}</td>
                        <td><span class="badge bg-info">{{ $kiosk->wilaya }}</span></td>
                        <td class="text-center">
                            @if($kiosk->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($kiosk->sales_count) }}</td>
                        <td class="text-end fw-bold text-success">
                            {{ number_format($kiosk->total_sales, 0, ',', ' ') }} DA
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.kiosks.show', $kiosk) }}" 
                                   class="btn btn-sm btn-info" title="Voir les détails et ventes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.kiosks.edit', $kiosk) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kiosks.destroy', $kiosk) }}" 
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
                                <i class="fas fa-store-alt-slash fa-2x mb-3"></i>
                                <p>Aucun kiosque trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $kiosks->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var tableSelector = '#kiosksDataTable'; 
        if ( ! $.fn.DataTable.isDataTable( tableSelector ) ) {
            $(tableSelector).DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
                // S'assurer que les options de pagination/recherche sont désactivées pour les données paginées par Laravel
                paging: false, 
                searching: false, 
                info: false, 
                ordering: true,
                
                // Définir explicitement 7 colonnes avec leur index pour plus de clarté
                columns: [
                    { orderable: true },    // 1. Nom
                    { orderable: true },    // 2. Gérant
                    { orderable: true },    // 3. Wilaya
                    { orderable: true },    // 4. Statut
                    { orderable: true },    // 5. Ventes Qty
                    { orderable: true },    // 6. Ventes Montant
                    { orderable: false }     // 7. Actions
                ],
                order: [[5, 'desc']] // Tri par Ventes (Montant)
            });
        }
    });
</script>
@endpush