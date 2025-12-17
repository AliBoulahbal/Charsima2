@extends('admin.layouts.admin')
@section('title', 'Gestion des Écoles')
@section('page-title', 'Écoles')

@section('page-actions')
    {{-- Le bouton d'export est déplacé ici --}}
    <a href="{{ route('admin.schools.export') }}" class="btn btn-success ms-2">
        <i class="fas fa-download"></i> Exporter
    </a>
    
    {{-- Le bouton d'importation ouvre la modale --}}
    <button type="button" class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-file-import"></i> Importer Excel
    </button>
    
    <a href="{{ route('admin.schools.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle École
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
                {{-- NOTE: $wilayas est passé par le contrôleur --}}
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

        {{-- Affichage des messages de session après importation --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-hover datatable" id="schoolsDataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Wilaya</th>
                        <th>Commune</th>
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
                        <td>{{ $school->commune }}</td>
                        <td>{{ $school->manager_name }}</td>
                        <td>{{ number_format($school->student_count) }}</td>
                        <td>
                            {{-- $school->deliveries_count vient du withCount dans le contrôleur --}}
                            <span class="badge bg-{{ $school->deliveries_count > 0 ? 'success' : 'secondary' }}">
                                {{ $school->deliveries_count }}
                            </span>
                        </td>
                        <td>
                            {{-- $school->total_delivered vient de l'addSelect dans le contrôleur --}}
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


{{-- ************************************************* --}}
{{-- MODALE D'IMPORTATION EXCEL (Placée en bas de page) --}}
{{-- ************************************************* --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Importer les Écoles depuis Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.schools.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p>Téléchargez votre fichier Excel (.xlsx ou .xls) contenant les données des écoles.</p>
                    <p class="text-danger">**Important :** Les colonnes du fichier Excel doivent s'appeler `البلدية`, `المدرسة`, `رقم الهاتف`, `الحي`, `المدير`, et `عدد التلاميذ`.</p>
                    
                    {{-- CHAMP AJOUTÉ: Sélection de la Wilaya du fichier --}}
                    <div class="mb-3">
                        <label for="import_wilaya" class="form-label">Wilaya du fichier *</label>
                        <select class="form-select @error('wilaya') is-invalid @enderror" 
                                id="import_wilaya" name="wilaya" required>
                            <option value="">Sélectionner la Wilaya</option>
                            @foreach($wilayas as $wilaya)
                                <option value="{{ $wilaya }}" {{ old('wilaya') == $wilaya ? 'selected' : '' }}>
                                    {{ $wilaya }}
                                </option>
                            @endforeach
                        </select>
                        @error('wilaya')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="file" class="form-label">Fichier Excel/CSV *</label>
                        <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file" required>
                        
                        {{-- Afficher les erreurs spécifiques du fichier d'import --}}
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Affichage des messages d'erreur si Laravel renvoie des erreurs de validation (spécifiques à l'import) --}}
                    @if ($errors->any() && session('error') !== null) 
                        <div class="alert alert-danger mt-3">
                            L'importation a échoué. Veuillez vérifier le fichier et vous assurer que toutes les colonnes sont présentes et uniques (Nom de l'école).
                            {{-- Optionnel: Afficher la première erreur détaillée pour aider l'utilisateur --}}
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @break
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-upload"></i> Lancer l'importation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette école ? Cette action est irréversible.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
    
    // Initialisation DataTables
    $(document).ready(function() {
        var tableSelector = '#schoolsDataTable'; 
        
        // Initialiser DataTables seulement si nécessaire (si vous n'utilisez pas la pagination Laravel)
        if ( ! $.fn.DataTable.isDataTable( tableSelector ) ) {
            $(tableSelector).DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
                paging: false, searching: false, ordering: true, info: false, // Ordering à true
                columns: [
                    null, null, null, null, null, null, // Colonnes de données
                    { orderable: true, searchable: false }, // Livraisons
                    { orderable: true, searchable: false }, // Montant
                    { orderable: false, searchable: false } // Actions
                ]
            });
        }

        // Afficher la modale d'importation automatiquement si l'importation précédente a échoué (pour voir les messages d'erreur)
        @if ($errors->any() || session('error'))
            var importModal = new bootstrap.Modal(document.getElementById('importModal'));
            importModal.show();
        @endif
    });
</script>
@endpush