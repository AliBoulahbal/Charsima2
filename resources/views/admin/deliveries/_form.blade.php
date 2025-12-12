@csrf

@if(isset($delivery) && $delivery->exists)
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="delivery_type" class="form-label">Type de livraison *</label>
            <select class="form-select @error('delivery_type') is-invalid @enderror" 
                    id="delivery_type" name="delivery_type" required
                    onchange="toggleDeliveryFields()">
                <option value="">Sélectionner un type</option>
                <option value="school" {{ old('delivery_type', $delivery->delivery_type ?? 'school') == 'school' ? 'selected' : '' }}>
                    Livraison école (distributeur)
                </option>
                <option value="kiosk" {{ old('delivery_type', $delivery->delivery_type ?? '') == 'kiosk' ? 'selected' : '' }}>
                    Vente kiosque
                </option>
                <option value="online" {{ old('delivery_type', $delivery->delivery_type ?? '') == 'online' ? 'selected' : '' }}>
                    Vente en ligne
                </option>
                <option value="teacher_free" {{ old('delivery_type', $delivery->delivery_type ?? '') == 'teacher_free' ? 'selected' : '' }}>
                    Carte enseignant gratuite
                </option>
            </select>
            @error('delivery_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4" id="school_field_container">
        <div class="mb-3">
            <label for="school_id" class="form-label">École *</label>
            <select class="form-select @error('school_id') is-invalid @enderror" 
                    id="school_id" name="school_id" required>
                <option value="">Sélectionner une école</option>
                @foreach($schools as $school)
                <option value="{{ $school->id }}" 
                        data-wilaya="{{ $school->wilaya }}"
                        {{ old('school_id', $delivery->school_id ?? '') == $school->id ? 'selected' : '' }}>
                    {{ $school->name }} - {{ $school->wilaya }}
                </option>
                @endforeach
            </select>
            @error('school_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="delivery_date" class="form-label">Date *</label>
            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                   id="delivery_date" name="delivery_date" 
                   value="{{ old('delivery_date', $delivery->delivery_date?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
            @error('delivery_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div id="distributor_fields" class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="distributor_id" class="form-label">Distributeur</label>
            <select class="form-select @error('distributor_id') is-invalid @enderror" 
                    id="distributor_id" name="distributor_id">
                <option value="">Sélectionner un distributeur</option>
                @foreach($distributors as $distributor)
                <option value="{{ $distributor->id }}" 
                        {{ old('distributor_id', $delivery->distributor_id ?? '') == $distributor->id ? 'selected' : '' }}>
                    {{ $distributor->name }} - {{ $distributor->wilaya }}
                </option>
                @endforeach
            </select>
            @error('distributor_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

{{-- LE CHAMP KIOSQUE EST MAINTENANT POPULÉ AVEC $kiosks --}}
<div id="kiosk_fields" class="row" style="display: none;">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="kiosk_id" class="form-label">Kiosque *</label>
            <select class="form-select @error('kiosk_id') is-invalid @enderror" 
                    id="kiosk_id" name="kiosk_id">
                <option value="">Sélectionner un kiosque</option>
                {{-- Boucle mise à jour pour utiliser la variable $kiosks --}}
                @foreach($kiosks ?? [] as $kiosk)
                <option value="{{ $kiosk->id }}" 
                        {{ old('kiosk_id', $delivery->kiosk_id ?? '') == $kiosk->id ? 'selected' : '' }}>
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

<div id="client_fields" class="row" style="display: none;">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="teacher_name" class="form-label">Nom du client *</label>
            <input type="text" class="form-control @error('teacher_name') is-invalid @enderror" 
                   id="teacher_name" name="teacher_name" 
                   value="{{ old('teacher_name', $delivery->teacher_name ?? '') }}">
            @error('teacher_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="teacher_phone" class="form-label">Téléphone *</label>
            <input type="tel" class="form-control @error('teacher_phone') is-invalid @enderror" 
                   id="teacher_phone" name="teacher_phone" 
                   value="{{ old('teacher_phone', $delivery->teacher_phone ?? '') }}">
            @error('teacher_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="customer_cin" class="form-label">CIN/Identifiant</label>
            <input type="text" class="form-control @error('customer_cin') is-invalid @enderror" 
                   id="customer_cin" name="customer_cin" 
                   value="{{ old('customer_cin', $delivery->customer_cin ?? '') }}">
            @error('customer_cin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="wilaya" class="form-label">Wilaya du client</label>
            <input type="text" class="form-control @error('wilaya') is-invalid @enderror" 
                   id="wilaya" name="wilaya" 
                   value="{{ old('wilaya', $delivery->wilaya ?? '') }}">
            @error('wilaya')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div id="teacher_fields" class="row" style="display: none;">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="teacher_subject" class="form-label">Matière enseignée</label>
            <input type="text" class="form-control @error('teacher_subject') is-invalid @enderror" 
                   id="teacher_subject" name="teacher_subject" 
                   value="{{ old('teacher_subject', $delivery->teacher_subject ?? '') }}">
            @error('teacher_subject')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="teacher_email" class="form-label">Email enseignant</label>
            <input type="email" class="form-control @error('teacher_email') is-invalid @enderror" 
                   id="teacher_email" name="teacher_email" 
                   value="{{ old('teacher_email', $delivery->teacher_email ?? '') }}">
            @error('teacher_email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantité *</label>
            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                   id="quantity" name="quantity" min="1" 
                   value="{{ old('quantity', $delivery->quantity ?? 1) }}" required
                   onchange="calculateTotal()">
            @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="mb-3">
            <label for="unit_price" class="form-label">Prix unitaire (DA) *</label>
            <input type="number" class="form-control @error('unit_price') is-invalid @enderror" 
                   id="unit_price" name="unit_price" min="0" step="50"
                   value="{{ old('unit_price', $delivery->unit_price ?? 500) }}" required
                   onchange="calculateTotal()">
            @error('unit_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="mb-3">
            <label for="discount_percentage" class="form-label">Remise (%)</label>
            <div class="input-group">
                <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" 
                       id="discount_percentage" name="discount_percentage" min="0" max="100" step="0.5"
                       value="{{ old('discount_percentage', $delivery->discount_percentage ?? 0) }}"
                       onchange="calculateTotal()">
                <span class="input-group-text">%</span>
                @error('discount_percentage')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <small class="form-text text-muted">Max 30% (forcé à 100% pour enseignants)</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body p-3">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td>Total:</td>
                        <td class="text-end" id="total_price_display">0 DA</td>
                        <input type="hidden" id="total_price" name="total_price" value="0">
                    </tr>
                    <tr>
                        <td>Remise:</td>
                        <td class="text-end text-success" id="discount_amount_display">0 DA</td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>À payer:</strong></td>
                        <td class="text-end fw-bold text-success" id="final_price_display">0 DA</td>
                        <input type="hidden" id="final_price" name="final_price" value="0">
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="notes" class="form-label">Notes</label>
    <textarea class="form-control @error('notes') is-invalid @enderror" 
              id="notes" name="notes" rows="2">{{ old('notes', $delivery->notes ?? '') }}</textarea>
    @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-between">
    <a href="{{ route('admin.deliveries.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Annuler
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> 
        {{ (isset($delivery) && $delivery->exists) ? 'Mettre à jour' : 'Créer la livraison' }}
    </button>
</div>

@push('scripts')
<script>
function toggleDeliveryFields() {
    const type = document.getElementById('delivery_type').value;
    
    // Éléments du champ École
    const schoolContainer = document.getElementById('school_field_container');
    const schoolSelect = document.getElementById('school_id');
    const schoolLabel = schoolContainer.querySelector('label');
    
    // Éléments Kiosque/Distributeur
    const kioskSelect = document.getElementById('kiosk_id');
    const distributorSelect = document.getElementById('distributor_id');

    // Masquer tous les champs conditionnels et réinitialiser la remise
    document.getElementById('distributor_fields').style.display = 'none';
    document.getElementById('kiosk_fields').style.display = 'none';
    document.getElementById('client_fields').style.display = 'none';
    document.getElementById('teacher_fields').style.display = 'none';
    
    document.getElementById('discount_percentage').readOnly = false;
    if (type !== 'teacher_free') {
        document.getElementById('discount_percentage').value = 0; 
    }
    
    // Réinitialisation des champs ID (pour ne pas envoyer de données croisées)
    schoolSelect.value = "";
    distributorSelect.value = "";
    kioskSelect.value = "";
    
    // Logique d'activation/désactivation du champ École
    if (type === 'school') {
        // Rendre l'école visible, obligatoire et activée
        schoolContainer.style.display = 'block';
        schoolSelect.setAttribute('required', 'required');
        schoolSelect.disabled = false;
        schoolLabel.textContent = 'École *';
        
        // Afficher le champ Distributeur
        document.getElementById('distributor_fields').style.display = 'block';
        
        // Rendre le distributeur obligatoire
        distributorSelect.setAttribute('required', 'required');
        kioskSelect.removeAttribute('required'); // S'assurer que Kiosque est facultatif

    } else {
        // Rendre l'école invisible, NON obligatoire et désactivée
        schoolContainer.style.display = 'none';
        schoolSelect.removeAttribute('required');
        schoolSelect.disabled = true;
        
        // Champs secondaires
        if (type === 'kiosk') {
            document.getElementById('kiosk_fields').style.display = 'block';
            document.getElementById('client_fields').style.display = 'block';
            kioskSelect.setAttribute('required', 'required'); // Kiosque obligatoire
            distributorSelect.removeAttribute('required'); // Distributeur facultatif

        } else if (type === 'online') {
            document.getElementById('client_fields').style.display = 'block';
            kioskSelect.removeAttribute('required');
            distributorSelect.removeAttribute('required');

        } else if (type === 'teacher_free') {
            document.getElementById('teacher_fields').style.display = 'block';
            document.getElementById('client_fields').style.display = 'block';
            
            // L'école est nécessaire pour le type enseignant (pour rattacher)
            schoolContainer.style.display = 'block';
            schoolSelect.setAttribute('required', 'required');
            schoolSelect.disabled = false;
            
            // Forcer le discount à 100% et le rendre non modifiable
            document.getElementById('discount_percentage').value = 100;
            document.getElementById('discount_percentage').readOnly = true;
            
            kioskSelect.removeAttribute('required');
            distributorSelect.removeAttribute('required');

        } else {
            // Type non sélectionné
            kioskSelect.removeAttribute('required');
            distributorSelect.removeAttribute('required');
        }
    }
    
    calculateTotal();
}

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    const discount = parseFloat(document.getElementById('discount_percentage').value) || 0;
    
    const totalPrice = quantity * unitPrice;
    const discountAmount = totalPrice * (discount / 100);
    const finalPrice = totalPrice - discountAmount;
    
    // Mettre à jour les affichages
    document.getElementById('total_price_display').textContent = formatCurrency(totalPrice) + ' DA';
    document.getElementById('discount_amount_display').textContent = '-' + formatCurrency(discountAmount) + ' DA';
    document.getElementById('final_price_display').textContent = formatCurrency(finalPrice) + ' DA';
    
    // Mettre à jour les champs cachés
    document.getElementById('total_price').value = totalPrice;
    document.getElementById('final_price').value = finalPrice;
}

function formatCurrency(amount) {
    return amount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

// Initialiser à l'ouverture de la page
document.addEventListener('DOMContentLoaded', function() {
    toggleDeliveryFields();
    calculateTotal();
    
    // Auto-remplir la wilaya du client selon l'école sélectionnée
    document.getElementById('school_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const wilaya = selectedOption ? selectedOption.getAttribute('data-wilaya') : '';
        document.getElementById('wilaya').value = wilaya || '';
    });
});
</script>
@endpush