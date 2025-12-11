@extends('admin.layouts.admin')
@section('title', 'Paiements en ligne en attente')
@section('page-title', 'Paiements en ligne - En attente')

@section('page-actions')
    <a href="{{ route('admin.online-payments.dashboard') }}" class="btn btn-info">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    <a href="{{ route('admin.online-payments.report') }}" class="btn btn-success ms-2">
        <i class="fas fa-file-export"></i> Rapport
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    En attente
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['total_pending'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Montant en attente
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['total_amount_pending'], 0, ',', ' ') }} DA
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Codes expirés
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['total_expired'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Confirmés aujourd'hui
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['total_confirmed_today'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i> Filtres</h6>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Recherche</label>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" placeholder="Code, client, téléphone...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="status">
                            <option value="">Tous</option>
                            <option value="payment_code_generated" {{ request('status') == 'payment_code_generated' ? 'selected' : '' }}>
                                En attente
                            </option>
                            <option value="payment_cancelled" {{ request('status') == 'payment_cancelled' ? 'selected' : '' }}>
                                Expirés/Annulés
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Wilaya</label>
                        <select class="form-select" name="wilaya">
                            <option value="">Toutes</option>
                            @foreach($wilayas as $wilaya)
                            <option value="{{ $wilaya }}" {{ request('wilaya') == $wilaya ? 'selected' : '' }}>
                                {{ $wilaya }}
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
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('admin.online-payments.pending') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau des paiements en attente -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date commande</th>
                        <th>Code paiement</th>
                        <th>Client</th>
                        <th>École</th>
                        <th>Wilaya</th>
                        <th>Montant</th>
                        <th>Expiration</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    @php
                        $isExpired = $payment->isPaymentCodeExpired();
                        $daysRemaining = $payment->days_remaining;
                    @endphp
                    <tr class="{{ $isExpired ? 'table-warning' : '' }}">
                        <td>
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <strong>{{ $payment->payment_code }}</strong>
                            <br>
                            <small class="text-muted">{{ $payment->transaction_id }}</small>
                        </td>
                        <td>
                            <strong>{{ $payment->teacher_name }}</strong>
                            <br>
                            <small>{{ $payment->teacher_phone }}</small>
                            @if($payment->customer_cin)
                                <br>
                                <small class="text-muted">CIN: {{ $payment->customer_cin }}</small>
                            @endif
                        </td>
                        <td>
                            @if($payment->school)
                                {{ $payment->school->name }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $payment->wilaya ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <strong class="text-success">{{ number_format($payment->final_price, 0, ',', ' ') }} DA</strong>
                            @if($payment->discount_percentage > 0)
                                <br>
                                <small class="text-success">-{{ $payment->discount_percentage }}%</small>
                            @endif
                        </td>
                        <td>
                            @if($payment->payment_code_expires_at)
                                {{ $payment->payment_code_expires_at->format('d/m/Y H:i') }}
                                <br>
                                @if($isExpired)
                                    <span class="badge bg-danger">Expiré</span>
                                @else
                                    <span class="badge bg-{{ $daysRemaining <= 1 ? 'warning' : 'success' }}">
                                        {{ $daysRemaining }} jour(s)
                                    </span>
                                @endif
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $payment->online_payment_status == 'payment_code_generated' ? 'warning' : 'danger' }}">
                                {{ $payment->online_payment_status_formatted }}
                            </span>
                            <br>
                            <span class="badge bg-secondary">{{ $payment->status_formatted }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.online-payments.confirm', $payment->payment_code) }}" 
                                   class="btn btn-success {{ $isExpired ? 'disabled' : '' }}" 
                                   title="Confirmer le paiement">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                                <a href="{{ route('admin.deliveries.show', $payment) }}" 
                                   class="btn btn-info" title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-primary" title="Générer proforma"
                                   onclick="generateProforma('{{ $payment->transaction_id }}')">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <button class="btn btn-warning" title="Renouveler code"
                                        onclick="renewCode('{{ $payment->transaction_id }}', '{{ $payment->teacher_phone }}')"
                                        {{ $isExpired ? '' : 'disabled' }}>
                                    <i class="fas fa-redo"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="py-5 text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                <p>Aucun paiement en attente</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateProforma(transactionId) {
    // Générer proforma
    window.open(`/api/online/order/download?transaction_id=${transactionId}&type=proforma`, '_blank');
}

function renewCode(transactionId, phone) {
    const newPhone = prompt('Entrez le numéro de téléphone du client pour vérification:', phone);
    if (newPhone && newPhone === phone) {
        // API call pour renouveler le code
        fetch(`/api/online/order/renew-code`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                transaction_id: transactionId,
                customer_phone: newPhone
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Nouveau code généré: ' + data.new_payment_code);
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        });
    } else if (newPhone) {
        alert('Numéro de téléphone incorrect');
    }
}

// Auto-refresh toutes les 60 secondes pour les paiements urgents
setTimeout(() => {
    const urgentRows = document.querySelectorAll('tr.table-warning');
    if (urgentRows.length > 0) {
        location.reload();
    }
}, 60000);
</script>
@endpush