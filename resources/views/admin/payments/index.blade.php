@extends('admin.layouts.admin')
@section('title', 'Gestion des Paiements')
@section('page-title', 'Paiements')

@section('page-actions')
    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau Paiement
    </a>
    
    {{-- CORRECTION: Remplacement du lien Exporter par un bouton modal --}}
    <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#exportModal">
        <i class="fas fa-download"></i> Exporter
    </button>
    
    <a href="{{ route('admin.payments.financial-report') }}" class="btn btn-info ms-2">
        <i class="fas fa-chart-pie"></i> Rapport Financier
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

        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Distributeur/Partenaire</th>
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
                        
                        {{-- Afficher le distributeur ou le kiosque (Logique du contrôleur) --}}
                        <td>
                            @if($payment->distributor)
                                <a href="{{ route('admin.distributors.show', $payment->distributor) }}">
                                    {{ $payment->distributor->user?->name ?? $payment->distributor->name }}
                                </a>
                                <small class="text-muted d-block">Distributeur</small>
                            @elseif($payment->kiosk)
                                <a href="{{ route('admin.kiosks.show', $payment->kiosk) }}">
                                    {{ $payment->kiosk->name }}
                                </a>
                                <small class="text-muted d-block">Kiosque</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        
                        {{-- Afficher la wilaya du partenaire --}}
                        <td>
                            @if($payment->distributor)
                                <span class="badge bg-info">{{ $payment->distributor->wilaya }}</span>
                            @elseif($payment->kiosk)
                                <span class="badge bg-danger">{{ $payment->kiosk->wilaya }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $payment->wilaya ?? 'N/A' }}</span>
                            @endif
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
                                    'card' => 'primary',
                                    'post_office' => 'secondary',
                                    'other' => 'dark'
                                ];
                                $methodLabels = [
                                    'cash' => 'Espèces', 'check' => 'Chèque', 'transfer' => 'Virement', 
                                    'card' => 'Carte', 'post_office' => 'Poste', 'other' => 'Autre'
                                ];
                            @endphp
                            <span class="badge bg-{{ $methodColors[$payment->method] ?? 'secondary' }}">
                                {{ $methodLabels[$payment->method] ?? $payment->method }}
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

        <div class="d-flex justify-content-center">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection


{{-- Modal d'Exportation --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Exporter les Paiements</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Le formulaire est soumis via GET pour inclure les filtres --}}
            <form id="export-form" method="GET" action="{{ route('admin.payments.export') }}">
                <div class="modal-body">
                    <p>Sélectionnez le format d'exportation. **Les filtres actuellement appliqués seront conservés.**</p>
                    <div class="mb-3">
                        <label for="export_format" class="form-label">Format de fichier *</label>
                        <select name="format" id="export_format" class="form-select" required>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Télécharger</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
    function confirmDelete(event) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce paiement ? Cette action est irréversible.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    // Ajoute les paramètres de l'URL (filtres) au formulaire d'exportation
    document.getElementById('exportModal').addEventListener('show.bs.modal', function () {
        const form = document.getElementById('export-form');
        // Supprimer les anciens inputs de filtres
        form.querySelectorAll('input[name][type="hidden"]').forEach(input => input.remove());

        // Récupérer les filtres actifs de l'URL
        const urlParams = new URLSearchParams(window.location.search);
        
        urlParams.forEach((value, key) => {
            // S'assurer que le filtre a une valeur et n'est pas "format"
            if (key !== 'format' && value) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = key;
                hiddenInput.value = value;
                form.appendChild(hiddenInput);
            }
        });
    });
</script>
@endpush