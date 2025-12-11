@extends('admin.layouts.admin')
@section('title', 'Nouveau Paiement')
@section('page-title', 'Nouveau Paiement')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Type de paiement -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="payment_type" class="form-label">Type de paiement *</label>
                        <select class="form-select @error('payment_type') is-invalid @enderror" 
                                id="payment_type" name="payment_type" required onchange="togglePaymentFields()">
                            <option value="">Sélectionner...</option>
                            <option value="distributor" {{ old('payment_type') == 'distributor' ? 'selected' : '' }}>
                                Paiement distributeur
                            </option>
                            <option value="kiosk" {{ old('payment_type') == 'kiosk' ? 'selected' : '' }}>
                                Paiement kiosque
                            </option>
                            <option value="online" {{ old('payment_type') == 'online' ? 'selected' : '' }}>
                                Paiement vente en ligne
                            </option>
                            <option value="other" {{ old('payment_type') == 'other' ? 'selected' : '' }}>
                                Autre paiement
                            </option>
                        </select>
                        @error('payment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Distributeur (conditionnel) -->
                <div class="col-md-4" id="distributor_field">
                    <div class="mb-3">
                        <label for="distributor_id" class="form-label">Distributeur *</label>
                        <select class="form-select @error('distributor_id') is-invalid @enderror" 
                                id="distributor_id" name="distributor_id">
                            <option value="">Sélectionner un distributeur</option>
                            @foreach($distributors as $distributor)
                            <option value="{{ $distributor->id }}" 
                                    data-wilaya="{{ $distributor->wilaya }}"
                                    {{ old('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                {{ $distributor->name }} - {{ $distributor->wilaya }}
                            </option>
                            @endforeach
                        </select>
                        @error('distributor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Kiosque (conditionnel) -->
                <div class="col-md-4" id="kiosk_field" style="display: none;">
                    <div class="mb-3">
                        <label for="kiosk_id" class="form-label">Kiosque *</label>
                        <select class="form-select @error('kiosk_id') is-invalid @enderror" 
                                id="kiosk_id" name="kiosk_id">
                            <option value="">Sélectionner un kiosque</option>
                            @foreach($kiosks ?? [] as $kiosk)
                            <option value="{{ $kiosk->id }}" 
                                    data-wilaya="{{ $kiosk->wilaya }}"
                                    {{ old('kiosk_id') == $kiosk->id ? 'selected' : '' }}>
                                {{ $kiosk->name }} - {{ $kiosk->wilaya }}
                            </option>
                            @endforeach
                        </select>
                        @error('kiosk_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- École -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">École associée *</label>
                        <select class="form-select @error('school_id') is-invalid @enderror" 
                                id="school_id" name="school_id" required onchange="updateWilayaFromSchool()">
                            <option value="">Sélectionner une école</option>
                            @foreach($schools as $school)
                            <option value="{{ $school->id }}" 
                                    data-wilaya="{{ $school->wilaya }}"
                                    {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }} - {{ $school->wilaya }}
                            </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            L'école à laquelle ce paiement est associé
                        </div>
                    </div>
                </div>

                <!-- Wilaya -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="wilaya" class="form-label">Wilaya *</label>
                        <select class="form-select @error('wilaya') is-invalid @enderror" 
                                id="wilaya" name="wilaya" required>
                            <option value="">Sélectionner une wilaya</option>
                            @foreach($wilayas as $wilaya)
                            <option value="{{ $wilaya }}" {{ old('wilaya') == $wilaya ? 'selected' : '' }}>
                                {{ $wilaya }}
                            </option>
                            @endforeach
                        </select>
                        @error('wilaya')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Wilaya de l'école ou du client
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Livraison associée (optionnel) -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="delivery_id" class="form-label">Livraison associée (optionnel)</label>
                        <select class="form-select @error('delivery_id') is-invalid @enderror" 
                                id="delivery_id" name="delivery_id">
                            <option value="">Sélectionner une livraison</option>
                            @if(old('school_id') || old('distributor_id'))
                                {{-- Les livraisons seront chargées dynamiquement via AJAX --}}
                            @endif
                        </select>
                        @error('delivery_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Pour lier ce paiement à une livraison spécifique
                        </div>
                    </div>
                </div>

                <!-- Nom de l'école (sauvegarde) -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_name" class="form-label">Nom de l'école (sauvegarde)</label>
                        <input type="text" class="form-control @error('school_name') is-invalid @enderror" 
                               id="school_name" name="school_name" 
                               value="{{ old('school_name') }}" readonly>
                        @error('school_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Sera automatiquement rempli lors de la sélection de l'école
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Montant -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant (DA) *</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" min="1" step="50"
                               value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Date de paiement -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Date de paiement *</label>
                        <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                               id="payment_date" name="payment_date" 
                               value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Méthode de paiement -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="method" class="form-label">Méthode de paiement *</label>
                        <select class="form-select @error('method') is-invalid @enderror" 
                                id="method" name="method" required>
                            <option value="">Sélectionner...</option>
                            @foreach($methods as $key => $method)
                            <option value="{{ $key }}" {{ old('method') == $key ? 'selected' : '' }}>
                                {{ $method }}
                            </option>
                            @endforeach
                        </select>
                        @error('method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Numéro de référence -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="reference_number" class="form-label">Numéro de référence</label>
                        <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                               id="reference_number" name="reference_number" 
                               value="{{ old('reference_number') }}" 
                               placeholder="Ex: REC202400001">
                        @error('reference_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="3" 
                          placeholder="Détails supplémentaires sur le paiement...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Information:</strong> Ce paiement sera automatiquement lié à l'école et la wilaya sélectionnées pour faciliter le suivi et les rapports.
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer le paiement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialiser à l'ouverture de la page
document.addEventListener('DOMContentLoaded', function() {
    togglePaymentFields();
    updateSchoolName();
    
    // Charger les kiosques si nécessaire
    loadKiosks();
});

// Basculer entre les champs distributeur/kiosque
function togglePaymentFields() {
    const paymentType = document.getElementById('payment_type').value;
    
    // Masquer tous les champs
    document.getElementById('distributor_field').style.display = 'none';
    document.getElementById('kiosk_field').style.display = 'none';
    
    // Rendre les champs obligatoires/optionnels
    const distributorSelect = document.getElementById('distributor_id');
    const kioskSelect = document.getElementById('kiosk_id');
    
    distributorSelect.required = false;
    kioskSelect.required = false;
    
    // Afficher le champ approprié
    switch(paymentType) {
        case 'distributor':
            document.getElementById('distributor_field').style.display = 'block';
            distributorSelect.required = true;
            break;
        case 'kiosk':
            document.getElementById('kiosk_field').style.display = 'block';
            kioskSelect.required = true;
            break;
        case 'online':
            // Pas de distributeur/kiosque pour les paiements en ligne
            break;
        case 'other':
            // Pas de distributeur/kiosque pour autres paiements
            break;
    }
}

// Mettre à jour la wilaya depuis l'école sélectionnée
function updateWilayaFromSchool() {
    const schoolSelect = document.getElementById('school_id');
    const selectedOption = schoolSelect.options[schoolSelect.selectedIndex];
    const wilaya = selectedOption.getAttribute('data-wilaya');
    
    if (wilaya) {
        const wilayaSelect = document.getElementById('wilaya');
        
        // Trouver et sélectionner l'option correspondante
        for (let i = 0; i < wilayaSelect.options.length; i++) {
            if (wilayaSelect.options[i].value === wilaya) {
                wilayaSelect.selectedIndex = i;
                break;
            }
        }
        
        // Mettre à jour le nom de l'école
        updateSchoolName();
        
        // Charger les livraisons pour cette école
        loadDeliveriesForSchool(schoolSelect.value);
    }
}

// Mettre à jour le nom de l'école dans le champ sauvegarde
function updateSchoolName() {
    const schoolSelect = document.getElementById('school_id');
    const selectedOption = schoolSelect.options[schoolSelect.selectedIndex];
    const schoolName = selectedOption.text.split(' - ')[0]; // Prendre juste le nom
    
    document.getElementById('school_name').value = schoolName;
}

// Charger les livraisons pour une école
function loadDeliveriesForSchool(schoolId) {
    if (!schoolId) return;
    
    const deliverySelect = document.getElementById('delivery_id');
    
    // Vider les options existantes sauf la première
    while (deliverySelect.options.length > 1) {
        deliverySelect.remove(1);
    }
    
    // AJAX pour charger les livraisons
    fetch(`/api/schools/${schoolId}/deliveries?simple=true`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.deliveries) {
                data.deliveries.forEach(delivery => {
                    const option = document.createElement('option');
                    option.value = delivery.id;
                    option.text = `Livraison #${delivery.id} - ${delivery.quantity} cartes - ${delivery.total_price} DA - ${delivery.delivery_date}`;
                    deliverySelect.add(option);
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des livraisons:', error);
        });
}

// Charger les kiosques via AJAX si non disponibles
function loadKiosks() {
    const kioskSelect = document.getElementById('kiosk_id');
    
    // Si déjà chargé, ne rien faire
    if (kioskSelect.options.length > 1) return;
    
    fetch('/api/kiosks?simple=true')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.kiosks) {
                data.kiosks.forEach(kiosk => {
                    const option = document.createElement('option');
                    option.value = kiosk.id;
                    option.text = `${kiosk.name} - ${kiosk.wilaya}`;
                    option.setAttribute('data-wilaya', kiosk.wilaya);
                    kioskSelect.add(option);
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des kiosques:', error);
        });
}

// Mettre à jour la wilaya depuis le distributeur/kiosque
document.getElementById('distributor_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const wilaya = selectedOption.getAttribute('data-wilaya');
    
    if (wilaya) {
        const wilayaSelect = document.getElementById('wilaya');
        for (let i = 0; i < wilayaSelect.options.length; i++) {
            if (wilayaSelect.options[i].value === wilaya) {
                wilayaSelect.selectedIndex = i;
                break;
            }
        }
    }
});

document.getElementById('kiosk_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const wilaya = selectedOption.getAttribute('data-wilaya');
    
    if (wilaya) {
        const wilayaSelect = document.getElementById('wilaya');
        for (let i = 0; i < wilayaSelect.options.length; i++) {
            if (wilayaSelect.options[i].value === wilaya) {
                wilayaSelect.selectedIndex = i;
                break;
            }
        }
    }
});
</script>
@endpush