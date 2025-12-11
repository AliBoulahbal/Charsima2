@extends('admin.layouts.admin')
@section('title', 'Détail Utilisateur')
@section('page-title', $user->name)

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Informations utilisateur -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i> Informations Personnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nom complet:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Téléphone:</th>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Rôle:</th>
                                <td>
                                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'info') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Créé le:</th>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Mis à jour:</th>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rôles et permissions -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i> Rôles et Permissions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Rôles attribués:</h6>
                        <ul class="list-group">
                            @foreach($user->getRoleNames() as $role)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $role }}
                                <form action="{{ route('admin.users.assign-role', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ $role }}">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Retirer ce rôle ?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Permissions:</h6>
                        <div class="row">
                            @foreach($user->getAllPermissions()->chunk(5) as $chunk)
                            <div class="col-6">
                                <ul class="list-unstyled">
                                    @foreach($chunk as $permission)
                                    <li><i class="fas fa-check text-success me-2"></i> {{ $permission->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar avec statistiques -->
    <div class="col-md-4">
        <!-- Profil distributeur -->
        @if($user->distributorProfile)
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Profil Distributeur</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Wilaya:</th>
                        <td>{{ $user->distributorProfile->wilaya }}</td>
                    </tr>
                    <tr>
                        <th>Téléphone:</th>
                        <td>{{ $user->distributorProfile->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Créé le:</th>
                        <td>{{ $user->distributorProfile->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        <!-- Statistiques -->
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    @if($user->deliveries->count() > 0)
                    <div class="mb-3">
                        <h3>{{ $user->deliveries->count() }}</h3>
                        <small class="text-muted">Livraisons</small>
                    </div>
                    <div class="mb-3">
                        <h3>{{ number_format($user->deliveries->sum('total_price'), 0, ',', ' ') }} DA</h3>
                        <small class="text-muted">Montant livré</small>
                    </div>
                    @endif
                    
                    @if($user->payments->count() > 0)
                    <div>
                        <h3>{{ number_format($user->payments->sum('amount'), 0, ',', ' ') }} DA</h3>
                        <small class="text-muted">Montant payé</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection