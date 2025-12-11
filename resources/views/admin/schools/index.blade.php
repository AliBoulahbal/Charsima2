@extends('admin.layouts.admin')
@section('title', 'Gestion des Écoles')
@section('page-title', 'Écoles')

@section('page-actions')
    <a href="{{ route('admin.schools.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle École
    </a>
    <a href="{{ route('admin.schools.export') }}" class="btn btn-success ms-2">
        <i class="fas fa-download"></i> Exporter
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
            {{-- AJOUT DE L'ID UNIQUE pour l'initialisation DataTables --}}
            <table class="table table-hover datatable" id="schoolsDataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Wilaya</th>
                        <th>District</th>
                        <th>Directeur</th>
                        <th>Élèves</th>
                        <th>Livraisons</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schools as $school)
                    <tr>
                        <td>{{ $school->id }}</td>
                        <td>
                            <strong>{{ $school->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $school->phone ?? 'Pas de téléphone' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $school->wilaya }}</span>
                        </td>
                        <td>{{ $school->district }}</td>
                        <td>{{ $school->manager_name }}</td>
                        <td>{{ number_format($school->student_count) }}</td>
                        <td>
                            <span class="badge bg-{{ $school->deliveries_count > 0 ? 'success' : 'secondary' }}">
                                {{ $school->deliveries_count }}
                            </span>
                        </td>
                        <td>
                            @if($school->total_delivered > 0)
                            <span class="text-success fw-bold">
                                {{ number_format($school->total_delivered, 0, ',', ' ') }} DA
                            </span>
                            @else
                            <span class="text-muted">0 DA</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.schools.show', $school) }}" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.schools.edit', $school) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.schools.destroy', $school) }}" 
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
                        <td colspan="9" class="text-center">
                            <div class="py-4 text-muted">
                                <i class="fas fa-school fa-2x mb-3"></i>
                                <p>Aucune école trouvée</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $schools->links() }}
        </div>
    </div>
</div>
@endsection


<table class="table table-hover datatable" id="schoolsDataTable">

</table>


@push('scripts')
<script>
    $(document).ready(function() {
        var tableSelector = '#schoolsDataTable'; 
        
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