@extends('admin.layouts.admin')
@section('title', 'Nouveau Paiement')
@section('page-title', 'Nouveau Paiement')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf
            
            <div class="row">
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

                <div class="col-md-4" id="distributor_field" 
                     style="display: {{ old('payment_type') == 'distributor' ? 'block' : 'none' }};">
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

                <div class="col-md-4" id="kiosk_field" 
                     style="display: {{ old('payment_type') == 'kiosk' ? 'block' : 'none' }};">
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
                <div class="col-md-6" id="school-group"
                     style="display: {{ old('payment_type') == 'distributor' ? 'block' : 'none' }};">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">École associée (Optionnel)</label>
                        <select class="form-select @error('school_id') is-invalid @enderror" 
                                id="school_id" name="school_id" onchange="updateWilayaFromSchool()">
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
                            Wilaya du partenaire ou du client
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
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
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant (DA) *</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" min="1" step="1"
                               value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

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
    
    // Si vous chargez des options via AJAX, assurez-vous de le faire ici.
});

// Basculer entre les champs distributeur/kiosque/école
function togglePaymentFields() {
    const paymentType = document.getElementById('payment_type').value;
    
    // Champs à manipuler
    const distributorField = document.getElementById('distributor_field');
    const kioskField = document.getElementById('kiosk_field');
    const schoolGroup = document.getElementById('school-group');
    
    const distributorSelect = document.getElementById('distributor_id');
    const kioskSelect = document.getElementById('kiosk_id');
    const schoolSelect = document.getElementById('school_id');

    // 1. Gestion des champs Partenaires (Distributeur/Kiosque)
    distributorField.style.display = (paymentType === 'distributor') ? 'block' : 'none';
    kioskField.style.display = (paymentType === 'kiosk') ? 'block' : 'none';
    
    distributorSelect.required = (paymentType === 'distributor');
    kioskSelect.required = (paymentType === 'kiosk');

    // 2. LOGIQUE D'AFFICHAGE DE L'ÉCOLE : Visible uniquement pour 'distributor'
    const showSchool = (paymentType === 'distributor');
    schoolGroup.style.display = showSchool ? 'block' : 'none'; 
    
    // 3. Réinitialiser les champs masqués et non pertinents
    if (paymentType !== 'distributor') {
        distributorSelect.value = '';
    }
    if (paymentType !== 'kiosk') {
        kioskSelect.value = '';
    }
    if (!showSchool) {
        schoolSelect.value = ''; // Désélectionner l'école si elle est masquée
        document.getElementById('school_name').value = ''; // Vider le nom de sauvegarde
        // Vider la sélection de livraison aussi
        const deliverySelect = document.getElementById('delivery_id');
        while (deliverySelect.options.length > 1) {
            deliverySelect.remove(1);
        }
    }
    
    // 4. Déclencher le changement de Wilaya si un partenaire est sélectionné
    const activePartnerSelect = (paymentType === 'distributor') 
        ? distributorSelect 
        : (paymentType === 'kiosk' ? kioskSelect : null);

    if (activePartnerSelect && activePartnerSelect.value) {
        activePartnerSelect.dispatchEvent(new Event('change'));
    }
    
    // Si aucun partenaire n'est sélectionné, la Wilaya reste manuelle (online/other)
}

// Mettre à jour la wilaya depuis l'école sélectionnée
function updateWilayaFromSchool() {
    const schoolSelect = document.getElementById('school_id');
    const selectedOption = schoolSelect.options[schoolSelect.selectedIndex];
    const wilaya = selectedOption ? selectedOption.getAttribute('data-wilaya') : null;
    const schoolName = selectedOption ? selectedOption.text.split(' - ')[0] : '';
    
    const wilayaSelect = document.getElementById('wilaya');
    
    if (wilaya) {
        // Sélectionner la wilaya correspondante
        wilayaSelect.value = wilaya;
        wilayaSelect.dispatchEvent(new Event('change')); 
    } else {
        // Optionnel : Réinitialiser la wilaya si l'école est désélectionnée
        // wilayaSelect.value = '';
    }
    
    // Mettre à jour le nom de l'école
    document.getElementById('school_name').value = schoolName;
    
    // Charger les livraisons pour cette école
    loadDeliveriesForSchool(schoolSelect.value);
}

// Charger les livraisons pour une école
function loadDeliveriesForSchool(schoolId) {
    // Le code de cette fonction (fetch AJAX) doit être dans votre script,
    // mais elle dépend de votre route /api/schools/{school}/deliveries
    if (!schoolId) return;
    
    const deliverySelect = document.getElementById('delivery_id');
    // Vider les options existantes sauf la première
    while (deliverySelect.options.length > 1) {
        deliverySelect.remove(1);
    }
    
    // Exemple de structure de chargement (assurez-vous que la route API existe)
    // fetch(`/api/schools/${schoolId}/deliveries?simple=true`)...
}

// Mettre à jour la wilaya depuis le distributeur/kiosque
document.getElementById('distributor_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const wilaya = selectedOption.getAttribute('data-wilaya');
    
    if (wilaya) {
        document.getElementById('wilaya').value = wilaya;
    }
});

document.getElementById('kiosk_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const wilaya = selectedOption.getAttribute('data-wilaya');
    
    if (wilaya) {
        document.getElementById('wilaya').value = wilaya;
    }
});

document.getElementById('payment_type').addEventListener('change', togglePaymentFields); 
document.getElementById('school_id').addEventListener('change', updateWilayaFromSchool);
</script>
@endpush