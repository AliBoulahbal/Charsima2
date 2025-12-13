@extends('admin.layouts.admin')
@section('title', 'Nouvelle Livraison')
@section('page-title', 'Nouvelle Livraison')

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.deliveries.store') }}" method="POST">
            @csrf 
            
            <div class="row">
                
                {{-- SECTION 1: Type et Dates --}}
                <h5 class="mb-3 text-primary"><i class="fas fa-truck me-2"></i> Informations de base</h5>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="delivery_type" class="form-label">Type de Livraison *</label>
                        <select class="form-select @error('delivery_type') is-invalid @enderror" 
                                id="delivery_type" name="delivery_type" required>
                            <option value="">Sélectionner...</option>
                            <option value="school" {{ old('delivery_type') == 'school' ? 'selected' : '' }}>Livraison École</option>
                            <option value="kiosk" {{ old('delivery_type') == 'kiosk' ? 'selected' : '' }}>Vente Kiosque</option>
                            <option value="online" {{ old('delivery_type') == 'online' ? 'selected' : '' }}>Vente en Ligne (Client)</option>
                            <option value="teacher_free" {{ old('delivery_type') == 'teacher_free' ? 'selected' : '' }}>Gratuit (Enseignant)</option>
                        </select>
                        @error('delivery_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="delivery_date" class="form-label">Date de Livraison *</label>
                        <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                               id="delivery_date" name="delivery_date" 
                               value="{{ old('delivery_date', now()->format('Y-m-d')) }}" required>
                        @error('delivery_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="col-md-4" id="distributor_field">
                    <div class="mb-3">
                        <label for="distributor_id" class="form-label">Distributeur *</label>
                        <select class="form-select @error('distributor_id') is-invalid @enderror" 
                                id="distributor_id" name="distributor_id">
                            <option value="">Sélectionner un distributeur</option>
                            @foreach($distributors as $distributor)
                            <option value="{{ $distributor->id }}" 
                                    {{ old('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                {{ $distributor->name }} - {{ $distributor->wilaya }}
                            </option>
                            @endforeach
                        </select>
                        @error('distributor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="col-md-4" id="kiosk_field" style="display:none;">
                    <div class="mb-3">
                        <label for="kiosk_id" class="form-label">Kiosque *</label>
                        <select class="form-select @error('kiosk_id') is-invalid @enderror" 
                                id="kiosk_id" name="kiosk_id">
                            <option value="">Sélectionner un kiosque</option>
                            @foreach($kiosks as $kiosk)
                            <option value="{{ $kiosk->id }}" 
                                    {{ old('kiosk_id') == $kiosk->id ? 'selected' : '' }}>
                                {{ $kiosk->name }} - {{ $kiosk->wilaya }}
                            </option>
                            @endforeach
                        </select>
                        @error('kiosk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                {{-- SECTION 2: Filtrage Wilaya/École --}}
                <h5 class="mb-3 mt-4 text-primary"><i class="fas fa-map-marker-alt me-2"></i> Destination</h5>
                
                {{-- CHAMP WILAYA (CORRIGÉ : utilise $wilayas) --}}
                <div class="col-md-4">
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
                        @error('wilaya')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- CHAMP ÉCOLE (Sera filtrée par JS) --}}
                <div class="col-md-4" id="school_field">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">École associée *</label>
                        <select class="form-select @error('school_id') is-invalid @enderror" 
                                id="school_id" name="school_id" disabled>
                            <option value="">Sélectionner une wilaya d'abord</option>
                        </select>
                        @error('school_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4" id="address_field" style="display:none;">
                    <div class="mb-3">
                        <label for="delivery_address" class="form-label">Adresse Complète</label>
                        <input type="text" class="form-control @error('delivery_address') is-invalid @enderror" 
                               id="delivery_address" name="delivery_address" 
                               value="{{ old('delivery_address') }}">
                        @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Prix et Quantité --}}
            <h5 class="mb-3 mt-4 text-primary"><i class="fas fa-coins me-2"></i> Détails financiers</h5>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantité (Cartes) *</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                               id="quantity" name="quantity" min="1" step="1"
                               value="{{ old('quantity', 1) }}" required>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="unit_price" class="form-label">Prix Unitaire (DA) *</label>
                        <input type="number" class="form-control @error('unit_price') is-invalid @enderror" 
                               id="unit_price" name="unit_price" min="0" step="1"
                               value="{{ old('unit_price', 150) }}" required>
                        @error('unit_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="discount_percentage" class="form-label">Remise (%)</label>
                        <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" 
                               id="discount_percentage" name="discount_percentage" min="0" max="100" step="1"
                               value="{{ old('discount_percentage', 0) }}">
                        @error('discount_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="final_price_display" class="form-label">Prix Final (DA) *</label>
                        <input type="text" class="form-control" id="final_price_display" value="0 DA" readonly>
                        {{-- Champs cachés pour le calcul --}}
                        <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price', 0) }}">
                        <input type="hidden" name="final_price" id="final_price" value="{{ old('final_price', 0) }}">
                    </div>
                </div>
            </div>

            {{-- SECTION 4: Notes et Enregistrement --}}
            <h5 class="mb-3 mt-4 text-primary"><i class="fas fa-file-alt me-2"></i> Notes</h5>
            
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.deliveries.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer la Livraison
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // =========================================================================
    // LOGIQUE DE FILTRAGE WILAYA / ÉCOLE (AJAX)
    // =========================================================================
    document.addEventListener('DOMContentLoaded', function() {
        const wilayaSelect = document.getElementById('wilaya');
        const schoolSelect = document.getElementById('school_id');
        const deliveryTypeSelect = document.getElementById('delivery_type');
        
        const distributorField = document.getElementById('distributor_field');
        const kioskField = document.getElementById('kiosk_field');
        const schoolField = document.getElementById('school_field');
        const addressField = document.getElementById('address_field');


        // URL de l'API pour récupérer les écoles
        const SCHOOLS_API_URL = '{{ route("admin.api.schools.by-wilaya") }}'; 

        // Fonction pour afficher/masquer les champs selon le type de livraison
        function toggleFields(type) {
            // Affichage des champs Wilaya/École/Distributeur/Kiosque/Adresse
            schoolField.style.display = (type === 'school' || type === 'teacher_free') ? 'block' : 'none';
            distributorField.style.display = (type === 'school') ? 'block' : 'none';
            kioskField.style.display = (type === 'kiosk') ? 'block' : 'none';
            addressField.style.display = (type === 'online' || type === 'kiosk') ? 'block' : 'none';
            
            // Logique de validation HTML
            schoolSelect.required = (type === 'school' || type === 'teacher_free');
            document.getElementById('distributor_id').required = (type === 'school');
            document.getElementById('kiosk_id').required = (type === 'kiosk');
            
            // Pour les ventes directes (kiosk, online), la wilaya est celle du client/kiosque
            wilayaSelect.required = true; 
            
            // Réinitialiser la liste des écoles si le champ est masqué
            if (type !== 'school' && type !== 'teacher_free') {
                schoolSelect.innerHTML = '<option value="">Sélectionner une wilaya d\'abord</option>';
                schoolSelect.disabled = true;
            }
        }
        
        // Fonction AJAX pour charger les écoles
        function loadSchools(wilaya, selectedSchoolId = null) {
            schoolSelect.innerHTML = '<option value="">Chargement...</option>'; 
            schoolSelect.disabled = true;

            if (!wilaya || (deliveryTypeSelect.value !== 'school' && deliveryTypeSelect.value !== 'teacher_free')) {
                schoolSelect.innerHTML = '<option value="">Sélectionner une wilaya d\'abord</option>';
                schoolSelect.disabled = true;
                return;
            }

            fetch(`${SCHOOLS_API_URL}?wilaya=${encodeURIComponent(wilaya)}`)
                .then(response => response.json())
                .then(data => {
                    schoolSelect.innerHTML = '<option value="">Sélectionner une école</option>';
                    if (data.schools && data.schools.length > 0) {
                        data.schools.forEach(school => {
                            const option = document.createElement('option');
                            option.value = school.id;
                            option.textContent = `${school.name} - ${school.wilaya}`;
                            
                            if (selectedSchoolId && school.id == selectedSchoolId) {
                                option.selected = true;
                            }
                            schoolSelect.appendChild(option);
                        });
                    } else {
                        schoolSelect.innerHTML = '<option value="">Aucune école trouvée dans cette wilaya</option>';
                    }
                    schoolSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erreur de chargement des écoles:', error);
                    schoolSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    schoolSelect.disabled = true;
                });
        }
        
        // =========================================================================
        // LOGIQUE DE CALCUL DU PRIX
        // =========================================================================
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('unit_price');
        const discountInput = document.getElementById('discount_percentage');
        const finalPriceDisplay = document.getElementById('final_price_display');
        const totalPriceHidden = document.getElementById('total_price');
        const finalPriceHidden = document.getElementById('final_price');

        function calculateFinalPrice() {
            const qty = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(priceInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            const totalPrice = qty * unitPrice;
            const finalPrice = totalPrice * (1 - discount / 100);

            // Mise à jour des champs cachés
            totalPriceHidden.value = totalPrice.toFixed(0);
            finalPriceHidden.value = finalPrice.toFixed(0);

            // Affichage utilisateur
            finalPriceDisplay.value = finalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' DA';
        }

        // Écouteurs pour le calcul
        quantityInput.addEventListener('input', calculateFinalPrice);
        priceInput.addEventListener('input', calculateFinalPrice);
        discountInput.addEventListener('input', calculateFinalPrice);


        // =========================================================================
        // INITIALISATION
        // =========================================================================

        // 1. Écouteurs principaux
        wilayaSelect.addEventListener('change', function() {
            if (deliveryTypeSelect.value === 'school' || deliveryTypeSelect.value === 'teacher_free') {
                loadSchools(this.value);
            }
        });
        
        deliveryTypeSelect.addEventListener('change', function() {
            toggleFields(this.value);
            // Recharger la liste des écoles si le type repasse à 'school'
            if ((this.value === 'school' || this.value === 'teacher_free') && wilayaSelect.value) {
                loadSchools(wilayaSelect.value);
            }
        });

        // 2. Initialisation au chargement de la page (si des valeurs old() existent)
        const initialType = deliveryTypeSelect.value;
        const initialWilaya = wilayaSelect.value;
        const initialSchoolId = '{{ old("school_id") }}'; // Récupère la valeur old() de school_id

        toggleFields(initialType);
        calculateFinalPrice();

        if ((initialType === 'school' || initialType === 'teacher_free') && initialWilaya) {
            loadSchools(initialWilaya, initialSchoolId);
        }
    });
</script>
@endpush