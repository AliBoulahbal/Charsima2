@extends('admin.layouts.admin')
@section('title', 'Dashboard Ventes en ligne')
@section('page-title', 'Dashboard Ventes en ligne')

@section('page-actions')
    <a href="{{ route('admin.online-payments.pending') }}" class="btn btn-warning">
        <i class="fas fa-clock"></i> Paiements en attente
    </a>
    <a href="{{ route('admin.online-payments.report') }}" class="btn btn-success ms-2">
        <i class="fas fa-chart-pie"></i> Rapports
    </a>
    <a href="{{ route('admin.kiosks.index') }}" class="btn btn-info ms-2">
        <i class="fas fa-store"></i> Kiosques
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Commandes totales
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_online_orders'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                                Paiements confirmés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_confirmed_payments'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                En attente de paiement
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_pending_payments'] }}
                            </div>
                            <div class="text-xs text-warning mt-1">
                                {{ number_format($stats['pending_revenue'], 0, ',', ' ') }} DA
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
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Chiffre d'affaires
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_revenue'], 0, ',', ' ') }} DA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deux colonnes -->
    <div class="row">
        <!-- Colonne gauche -->
        <div class="col-lg-8">
            <!-- Paiements récents -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Paiements récemment confirmés</h6>
                    <a href="{{ route('admin.online-payments.report') }}" class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Code</th>
                                    <th>Client</th>
                                    <th>École</th>
                                    <th>Montant</th>
                                    <th>Méthode</th>
                                    <th>Reçu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_confirmation_date->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $payment->payment_code }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->transaction_id }}</small>
                                    </td>
                                    <td>
                                        {{ $payment->teacher_name }}
                                        <br>
                                        <small>{{ $payment->teacher_phone }}</small>
                                    </td>
                                    <td>{{ $payment->school->name ?? 'N/A' }}</td>
                                    <td class="text-success">{{ number_format($payment->final_price, 0, ',', ' ') }} DA</td>
                                    <td>
                                        <span class="badge bg-info">{{ $payment->payment_method_formatted }}</span>
                                    </td>
                                    <td>
                                        @if($payment->payment_receipt_number)
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="printReceipt('{{ $payment->transaction_id }}')">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Paiements urgents (expirent bientôt) -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">Paiements urgents (expirent dans 24h)</h6>
                    <a href="{{ route('admin.online-payments.pending') }}" class="btn btn-sm btn-outline-warning">
                        Gérer
                    </a>
                </div>
                <div class="card-body">
                    @if($urgentPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Client</th>
                                    <th>École</th>
                                    <th>Montant</th>
                                    <th>Expire dans</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($urgentPayments as $payment)
                                @php
                                    $hoursRemaining = now()->diffInHours($payment->payment_code_expires_at);
                                @endphp
                                <tr class="{{ $hoursRemaining < 12 ? 'table-warning' : '' }}">
                                    <td>
                                        <strong>{{ $payment->payment_code }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        {{ $payment->teacher_name }}
                                        <br>
                                        <small>{{ $payment->teacher_phone }}</small>
                                    </td>
                                    <td>{{ $payment->school->name ?? 'N/A' }}</td>
                                    <td class="text-success">{{ number_format($payment->final_price, 0, ',', ' ') }} DA</td>
                                    <td>
                                        <span class="badge bg-{{ $hoursRemaining < 6 ? 'danger' : 'warning' }}">
                                            {{ $hoursRemaining }} heure(s)
                                        </span>
                                        <br>
                                        <small>{{ $payment->payment_code_expires_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.online-payments.confirm', $payment->payment_code) }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-check-circle"></i> Confirmer
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>
                        <p>Aucun paiement urgent pour le moment</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Colonne droite -->
        <div class="col-lg-4">
            <!-- Statistiques par méthode de paiement -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Par méthode de paiement</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Méthode</th>
                                    <th class="text-end">Nombre</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentMethodsStats as $stat)
                                <tr>
                                    <td>
                                        @php
                                            $methodLabels = [
                                                'cash' => 'Espèces',
                                                'bank_transfer' => 'Virement',
                                                'check' => 'Chèque',
                                                'post_office' => 'Poste',
                                            ];
                                        @endphp
                                        {{ $methodLabels[$stat->payment_method] ?? $stat->payment_method }}
                                    </td>
                                    <td class="text-end">{{ $stat->count }}</td>
                                    <td class="text-end text-success">{{ number_format($stat->amount, 0, ',', ' ') }} DA</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Top wilayas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Wilayas</h6>
                </div>
                <div class="card-body">
                    @foreach($wilayaStats as $wilaya)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small">
                            <span>{{ $wilaya->wilaya }}</span>
                            <span>{{ number_format($wilaya->amount, 0, ',', ' ') }} DA</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-info" role="progressbar" 
                                 style="width: {{ ($wilaya->amount / max($stats['total_revenue'], 1)) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.deliveries.create') }}?delivery_type=online" 
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouvelle commande en ligne
                        </a>
                        <a href="{{ route('admin.deliveries.create') }}?delivery_type=teacher_free" 
                           class="btn btn-success">
                            <i class="fas fa-user-graduate"></i> Carte enseignant gratuite
                        </a>
                        <button class="btn btn-info" onclick="generateDailyReport()">
                            <i class="fas fa-file-excel"></i> Rapport quotidien
                        </button>
                        <button class="btn btn-warning" onclick="checkExpiredCodes()">
                            <i class="fas fa-sync"></i> Vérifier codes expirés
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function printReceipt(transactionId) {
    window.open(`/api/online/order/download?transaction_id=${transactionId}&type=receipt`, '_blank');
}

function generateDailyReport() {
    const date = prompt('Entrez la date pour le rapport (YYYY-MM-DD):', new Date().toISOString().split('T')[0]);
    if (date) {
        window.open(`{{ route('admin.online-payments.report') }}?date_from=${date}&date_to=${date}`, '_blank');
    }
}

function checkExpiredCodes() {
    if (confirm('Voulez-vous vérifier et marquer automatiquement les codes expirés?')) {
        fetch('{{ route("admin.online-payments.check-expired") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`${data.expired_count} codes expirés ont été mis à jour`);
                location.reload();
            }
        });
    }
}

// Auto-refresh du dashboard toutes les 5 minutes
setTimeout(() => {
    location.reload();
}, 300000);
</script>
@endpush