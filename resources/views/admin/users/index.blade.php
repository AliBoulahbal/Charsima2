@extends('admin.layouts.admin')
@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Utilisateurs')

@section('page-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel Utilisateur
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <!-- Filtres -->
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
                <select class="form-select" onchange="window.location.href = this.value">
                    <option value="{{ route('admin.users.index') }}">Tous les rôles</option>
                    @foreach(['admin', 'manager', 'distributor'] as $role)
                    <option value="{{ route('admin.users.index', ['role' => $role]) }}"
                            {{ request('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tableau -->
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Téléphone</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 36px; height: 36px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->distributorProfile)
                                    <br>
                                    <small class="text-muted">{{ $user->distributorProfile->wilaya }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'info') }}">
                                {{ $user->role }}
                            </span>
                            <br>
                            <small class="text-muted">
                                @foreach($user->getRoleNames() as $role)
                                {{ $role }}@if(!$loop->last), @endif
                                @endforeach
                            </small>
                        </td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" 
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
                                <i class="fas fa-users fa-2x mb-3"></i>
                                <p>Aucun utilisateur trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

<table class="table table-hover datatable" id="usersDataTable"> 

</table>


@push('scripts')
<script>
    $(document).ready(function() {
        var tableSelector = '#usersDataTable'; 
        
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