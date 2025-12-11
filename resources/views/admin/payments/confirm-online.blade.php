@extends('admin.layouts.admin')
@section('title', 'Confirmer paiement: ' . $sale->payment_code)
@section('page-title', 'Confirmer paiement en ligne')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Confirmation de paiement</h5>
            </div>
            <div class="card-body">
                <!-- Alertes -->
                @if($isExpired)
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention!</strong> Ce code de paiement a expiré. Veuillez demander au client de générer un nouveau code.
                </div>
                @elseif($sale->online_payment_status === 'payment_confirmed')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Ce paiement a déjà été confirmé le {{ $sale->payment_confirmation_date->format('d/m/Y H:i') }}
                </div>
                @endif

                <!-- Récapitulatif de la commande -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Détails de la commande</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Code paiement:</th>
                                        <td><strong class="text-primary">{{ $sale->payment_code }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>N° Commande:</th>
                                        <td>{{ $sale->transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date commande:</th>
                                        <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Client:</th>
                                        <td>
                                            <strong>{{ $sale->teacher_name }}</strong>
                                            <br>
                                            <small>{{ $sale->teacher_phone }}</small>
                                            @if($sale->customer_cin)
                                            <br>
                                            <small>CIN: {{ $sale->customer_cin }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">École:</th>
                                        <td>{{ $sale->school->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Wilaya:</th>
                                        <td>{{ $sale->wilaya }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expiration:</th>
                                        <td>
                                            {{ $sale->payment_code_expires_at->format('d/m/Y à H:i') }}
                                            <br>
                                            <span class="badge bg-{{ $sale->days_remaining <= 1 ? 'warning' : 'success' }}">
                                                {{ $sale->days_remaining }} jour(s) restant(s)
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Montant dû:</th>
                                        <td class="h4 text-success">
                                            {{ number_format($sale->final_price, 0, ',', ' ') }} DA
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Détails produits -->
                        <div class="mt-3">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr class="table-light">
                                        <th>Description</th>
                                        <th width="100" class="text-center">Quantité</th>
                                        <th width="150" class="text-center">Prix unitaire</th>
                                        <th width="150" class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Carte scolaire - {{ $sale->school->name }}</td>
                                        <td class="text-center">{{ $sale->quantity }}</td>
                                        <td class="text-center">{{ number_format($sale->unit_price, 0, ',', ' ') }} DA</td>
                                        <td class="text-center">{{ number_format($sale->total_price, 0, ',', ' ') }} DA</td>
                                    </tr>
                                    @if($sale->discount_percentage > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">Remise ({{ $sale->discount_percentage }}%)</td>
                                        <td class="text-center text-success">
                                            -{{ number_format($sale->discount_amount, 0, ',', ' ') }} DA
                                        </td>
                                    </tr>
                                    @endif
                                    <tr class="table-active">
                                        <td colspan="3" class="text-end fw-bold">TOTAL À PAYER</td>
                                        <td class="text-center fw-bold text-success">
                                            {{ number_format($sale->final_price, 0, ',', ' ') }} DA
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de confirmation -->
                @if(!$isExpired && $sale->online_payment_status !== 'payment_confirmed')
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-money-check-alt me-2"></i> Informations de paiement</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.online-payments.process', $sale->payment_code) }}" method="POST">
                            @csrf
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Méthode de paiement *</label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                                            name="payment_method" required>
                                        <option value="">Sélectionner...</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Chèque</option>
                                        <option value="post_office" {{ old('payment_method') == 'post_office' ? 'selected' : '' }}>Poste Algérienne</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date du paiement *</label>
                                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                           name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">N° de reçu/reference *</label>
                                    <input type="text" class="form-control @error('payment_receipt_number') is-invalid @enderror" 
                                           name="payment_receipt_number" value="{{ old('payment_receipt_number') }}" 
                                           placeholder="Ex: REC202400001" required>
                                    @error('payment_receipt_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">N° bordereau (si virement)</label>
                                    <input type="text" class="form-control @error('bank_deposit_slip') is-invalid @enderror" 
                                           name="bank_deposit_slip" value="{{ old('bank_deposit_slip') }}" 
                                           placeholder="Ex: BORD123456">
                                    @error('bank_deposit_slip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes de vérification</label>
                                <textarea class="form-control @error('payment_verification_notes') is-invalid @enderror" 
                                          name="payment_verification_notes" rows="2" 
                                          placeholder="Détails supplémentaires...">{{ old('payment_verification_notes') }}</textarea>
                                @error('payment_verification_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="confirm_delivery" 
                                       name="confirm_delivery" value="1" {{ old('confirm_delivery') ? 'checked' : '' }}>
                                <label class="form-check-label" for="confirm_delivery">
                                    Marquer la livraison comme confirmée (cartes prêtes à être livrées)
                                </label>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Après confirmation, un reçu sera généré et la commande sera marquée comme payée.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.online-payments.pending') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> Confirmer le paiement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Colonne droite - Actions rapides -->
    <div class="col-md-4">
        <!-- Instructions de paiement originales -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Instructions originales</h6>
            </div>
            <div class="card-body">
                @php
                    $instructions = $sale->payment_instructions;
                @endphp
                @if($instructions)
                <p><strong>Code:</strong> {{ $instructions['code'] }}</p>
                <p><strong>Montant:</strong> {{ $instructions['amount'] }}</p>
                <p><strong>Expiration:</strong> {{ $instructions['expires_at'] }}</p>
                <hr>
                <p class="mb-1"><strong>Méthodes acceptées:</strong></p>
                <ul class="small">
                    <li><strong>Virement:</strong> {{ $instructions['payment_methods']['bank_transfer']['bank_name'] }}</li>
                    <li><strong>Poste:</strong> CCP: {{ $instructions['payment_methods']['post_office']['ccp_number'] }}</li>
                    <li><strong>Espèces:</strong> Aux guichets désignés</li>
                </ul>
                @endif
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card shadow mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i> Actions rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-info" onclick="printProforma()">
                        <i class="fas fa-print"></i> Imprimer proforma
                    </button>
                    
                    <a href="tel:{{ $sale->teacher_phone }}" class="btn btn-success">
                        <i class="fas fa-phone"></i> Appeler le client
                    </a>
                    
                    <a href="https://wa.me/213{{ substr($sale->teacher_phone, 1) }}?text=Bonjour%20{{ urlencode($sale->teacher_name) }}%20-%20Votre%20paiement%20a%20été%20confirmé%20avec%20succès" 
                       target="_blank" class="btn btn-success" style="background-color: #25D366; border-color: #25D366;">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    
                    <a href="mailto:?subject=Confirmation%20paiement%20{{ $sale->payment_code }}&body=Bonjour,%0A%0AVotre%20paiement%20a%20été%20confirmé.%0ACode:%20{{ $sale->payment_code }}%0AMontant:%20{{ number_format($sale->final_price, 0, ',', ' ') }}%20DA%0A%0ACordialement" 
                       class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Envoyer confirmation
                    </a>
                    
                    @if(!$isExpired && $sale->online_payment_status !== 'payment_confirmed')
                    <button class="btn btn-danger" onclick="cancelOrder()">
                        <i class="fas fa-times-circle"></i> Annuler la commande
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historique si déjà payé -->
        @if($sale->online_payment_status === 'payment_confirmed')
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i> Historique de paiement</h6>
            </div>
            <div class="card-body">
                <p><strong>Date confirmation:</strong> {{ $sale->payment_confirmation_date->format('d/m/Y H:i') }}</p>
                <p><strong>Méthode:</strong> {{ $sale->payment_method_formatted }}</p>
                <p><strong>N° reçu:</strong> {{ $sale->payment_receipt_number }}</p>
                @if($sale->bank_deposit_slip)
                    <p><strong>Bordereau:</strong> {{ $sale->bank_deposit_slip }}</p>
                @endif
                @if($sale->payment_confirmed_by)
                    <p><strong>Confirmé par:</strong> {{ $sale->paymentConfirmedBy->name ?? 'N/A' }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function printProforma() {
    window.open(`/api/online/order/download?transaction_id={{ $sale->transaction_id }}&type=proforma`, '_blank');
}

function cancelOrder() {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande? Cette action est irréversible.')) {
        const reason = prompt('Veuillez indiquer la raison de l\'annulation:');
        if (reason !== null) {
            // API call pour annuler
            fetch(`/api/online/order/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    transaction_id: '{{ $sale->transaction_id }}',
                    customer_phone: '{{ $sale->teacher_phone }}',
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Commande annulée avec succès');
                    window.location.href = '{{ route('admin.online-payments.pending') }}';
                } else {
                    alert('Erreur: ' + data.message);
                }
            });
        }
    }
}
</script>
@endpush