@extends('admin.layouts.admin')
@section('title', 'Gestion des Livraisons')
@section('page-title', 'Livraisons')

@section('page-actions')
    <a href="{{ route('admin.deliveries.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle Livraison
    </a>
    <a href="{{ route('admin.deliveries.export') }}" class="btn btn-success ms-2">
        <i class="fas fa-download"></i> Exporter
    </a>
    <a href="{{ route('admin.deliveries.statistics') }}" class="btn btn-info ms-2">
        <i class="fas fa-chart-bar"></i> Statistiques
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
                        <label class="form-label">École</label>
                        <select class="form-select" name="school_id">
                            <option value="">Toutes les écoles</option>
                            @foreach($schools as $school)
                            <option value="{{ $school->id }}" 
                                    {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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
                        <label class="form-label">Wilaya</label>
                        <select class="form-select" name="wilaya">
                            <option value="">Toutes</option>
                            @foreach($wilayas as $wilaya)
                            <option value="{{ $wilaya }}" 
                                    {{ request('wilaya') == $wilaya ? 'selected' : '' }}>
                                {{ $wilaya }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.deliveries.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(request()->anyFilled(['school_id', 'distributor_id', 'date_from', 'date_to', 'wilaya']))
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center py-2">
                        <h6 class="mb-0">{{ $stats['total'] }}</h6>
                        <small>Livraisons</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center py-2">
                        <h6 class="mb-0">{{ number_format($stats['total_quantity']) }}</h6>
                        <small>Cartes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center py-2">
                        <h6 class="mb-0">{{ number_format($stats['total_amount'], 0, ',', ' ') }} DA</h6>
                        <small>Montant total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center py-2">
                        <h6 class="mb-0">{{ number_format($stats['total_amount'] / max($stats['total_quantity'], 1), 0, ',', ' ') }} DA</h6>
                        <small>Moyenne par carte</small>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover datatable" id="deliveriesDataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>École / Type</th>
                        <th>Partenaire</th>
                        <th>Wilaya</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                    <tr>
                        <td>{{ $delivery->id }}</td>
                        <td>
                            {{ $delivery->delivery_date->format('d/m/Y') }}
                            <br>
                            <small class="text-muted">{{ $delivery->created_at->format('H:i') }}</small>
                        </td>
                        
                        {{-- CORRECTION 1: Afficher l'école ou le type si non applicable --}}
                        <td>
                            @if($delivery->school)
                                <a href="{{ route('admin.schools.show', $delivery->school) }}">
                                    {{ $delivery->school->name }}
                                </a>
                            @else
                                <span class="badge bg-secondary">{{ $delivery->delivery_type_formatted ?? $delivery->delivery_type }}</span>
                            @endif
                        </td>
                        
                        {{-- CORRECTION 2: Afficher Distributeur, Kiosque ou Vente Directe --}}
                        <td>
                            @if($delivery->distributor)
                                <a href="{{ route('admin.distributors.show', $delivery->distributor) }}">
                                    {{ $delivery->distributor->user->name ?? $delivery->distributor->name }}
                                </a>
                            @elseif($delivery->kiosk)
                                <span class="text-muted">Kiosque: </span>
                                <a href="{{ route('admin.kiosks.show', $delivery->kiosk) }}">
                                    {{ $delivery->kiosk->name }}
                                </a>
                            @elseif($delivery->delivery_type === 'online')
                                <span class="text-muted">Vente en ligne</span>
                            @elseif($delivery->delivery_type === 'teacher_free')
                                <span class="text-muted">Enseignant</span>
                            @else
                                N/A
                            @endif
                        </td>
                        
                        {{-- CORRECTION 3: Afficher Wilaya de l'école/client/kiosque --}}
                        <td>
                            @if($delivery->school)
                                <span class="badge bg-info">{{ $delivery->school->wilaya }} (École)</span>
                            @elseif($delivery->kiosk)
                                <span class="badge bg-info">{{ $delivery->kiosk->wilaya }} (Kiosque)</span>
                            @elseif($delivery->wilaya)
                                <span class="badge bg-info">{{ $delivery->wilaya }} (Client)</span>
                            @else
                                N/A
                            @endif
                        </td>
                        
                        <td>
                            <span class="badge bg-secondary">{{ number_format($delivery->quantity) }}</span>
                        </td>
                        <td>{{ number_format($delivery->unit_price, 0, ',', ' ') }} DA</td>
                        <td>
                            <span class="fw-bold text-success">
                                {{-- Utiliser final_price si c'est la valeur réelle payée (Mettre à jour si le modèle utilise final_price pour les statistiques) --}}
                                {{ number_format($delivery->total_price, 0, ',', ' ') }} DA
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.deliveries.edit', $delivery) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.deliveries.destroy', $delivery) }}" 
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
                                <i class="fas fa-box-open fa-2x mb-3"></i>
                                <p>Aucune livraison trouvée</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $deliveries->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var tableSelector = '#deliveriesDataTable'; 
        
        if ( ! $.fn.DataTable.isDataTable( tableSelector ) ) {
            $(tableSelector).DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
                paging: false, searching: false, ordering: false, info: false,
                // Définition de 9 colonnes:
                columns: [null, null, null, null, null, null, null, null, { orderable: false, searchable: false }]
            });
        }
    });
</script>
@endpush