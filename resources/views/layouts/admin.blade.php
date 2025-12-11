<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Madaure Distribution</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    @stack('styles')

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #343a40; /* Dark background */
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            transition: all 0.3s;
            z-index: 1000;
        }
        .sidebar a {
            color: #adb5bd;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover,
        .sidebar a.active {
            color: #fff;
            background: #495057;
        }
        .content-wrapper {
            margin-left: 250px;
            padding-top: 56px; /* Espace pour la navbar fixe */
            transition: all 0.3s;
        }
        .topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 999;
            transition: all 0.3s;
        }
        /* Style général du contenu de la page */
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column p-3">
        <h3 class="text-white text-center py-3">
            <i class="fas fa-truck-moving me-2"></i> Admin Panel
        </h3>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            
            {{-- KIOSQUES & VENTES --}}
            <li>
                <a href="{{ route('admin.kiosks.index') }}" class="nav-link {{ request()->routeIs('admin.kiosks.*') ? 'active' : '' }}">
                    <i class="fas fa-store me-2"></i> Kiosques & Ventes
                </a>
            </li>

            {{-- LIVRAISONS --}}
            <li>
                <a href="{{ route('admin.deliveries.index') }}" class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes me-2"></i> Livraisons
                </a>
            </li>
            
            {{-- PAIEMENTS --}}
            <li>
                <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave me-2"></i> Paiements
                </a>
            </li>

            {{-- DISTRIBUTEURS (UNE SEULE OCCURRENCE) --}}
            <li>
                <a href="{{ route('admin.distributors.index') }}" class="nav-link {{ request()->routeIs('admin.distributors.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Distributeurs
                </a>
            </li>

            {{-- ÉCOLES --}}
            <li>
                <a href="{{ route('admin.schools.index') }}" class="nav-link {{ request()->routeIs('admin.schools.*') ? 'active' : '' }}">
                    <i class="fas fa-school me-2"></i> Écoles
                </a>
            </li>
            
            {{-- GESTION DES ADMins --}}
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
            <li>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield me-2"></i> Gestion des Admins
                </a>
            </li>
            @endif
        </ul>

        <div class="mt-auto">
            <hr class="text-white-50">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion ({{ Auth::user()->name ?? 'Invité' }})
            </a>
        </div>
    </div>

    <div class="content-wrapper">
        <nav class="topbar navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <h2 class="h4 mb-0 text-gray-800">@yield('page-title', 'Tableau de Bord')</h2>
                </a>
                <div class="d-flex align-items-center">
                    @yield('page-actions')
                </div>
            </div>
        </nav>

        <div class="content container-fluid">
            <div class="mt-2">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Erreur !</h4>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
            
            @yield('content')
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        
        // ATTENTION: Le script d'initialisation DataTables générique a été retiré ici pour 
        // ÉVITER LES CONFLITS avec les tables utilisant la pagination Laravel (comme les distributeurs).
        // Chaque vue d'index DOIT maintenant inclure son propre script d'initialisation 
        // DataTables spécifique (avec les options `paging: false`, `searching: false`, etc.)
        // via la directive Blade @push('scripts').

        // Confirmation de suppression (rendue globale)
        function confirmDelete(event) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.')) {
                event.preventDefault();
                return false;
            }
            return true;
        }

    </script>
    
    @stack('scripts')
</body>
</html>