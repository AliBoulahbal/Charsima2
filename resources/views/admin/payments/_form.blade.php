@csrf

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="distributor_id" class="form-label">Distributeur *</label>
        <select class="form-select @error('distributor_id') is-invalid @enderror" 
                id="distributor_id" name="distributor_id" required>
            <option value="">Sélectionner un distributeur</option>
            @foreach($distributors as $distributor)
            <option value="{{ $distributor->id }}" 
                    {{ old('distributor_id', $payment->distributor_id ?? request('distributor_id')) == $distributor->id ? 'selected' : '' }}>
                {{ $distributor->name }} - {{ $distributor->wilaya }}
                @if($distributor->total_due > 0)
                (Solde dû: {{ number_format($distributor->total_due, 0, ',', ' ') }} DA)
                @endif
            </option>
            @endforeach
        </select>
        @error('distributor_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="amount" class="form-label">Montant (DA) *</label>
        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
       id="amount" name="amount" min="1" step="1" 
       value="{{ old('amount', $payment->amount ?? '') }}" required>
        @error('amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="payment_date" class="form-label">Date de paiement *</label>
        <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
               id="payment_date" name="payment_date" 
               {{-- CORRECTION 2: Utilisation de l'opérateur null-safe pour le formatage de la date --}}
               value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
        @error('payment_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="method" class="form-label">Méthode de paiement *</label>
        <select class="form-select @error('method') is-invalid @enderror" 
                id="method" name="method" required>
            <option value="">Sélectionner une méthode</option>
            @foreach($methods as $key => $method)
            <option value="{{ $key }}" 
                    {{ old('method', $payment->method ?? '') == $key ? 'selected' : '' }}>
                {{ $method }}
            </option>
            @endforeach
        </select>
        @error('method')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="note" class="form-label">Note (optionnel)</label>
        <input type="text" class="form-control @error('note') is-invalid @enderror" 
               id="note" name="note" value="{{ old('note', $payment->note ?? '') }}">
        @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="card bg-light mb-4">
    <div class="card-body">
        <h6>Informations du distributeur sélectionné:</h6>
        <div id="distributor-info" class="text-muted">
            Sélectionnez un distributeur pour voir ses informations
        </div>
    </div>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> {{ $payment->exists ? 'Mettre à jour' : 'Enregistrer' }}
    </button>
</div>

@push('scripts')
<script>
    const distributors = @json($distributors->keyBy('id'));
    
    function updateDistributorInfo() {
        const distributorId = document.getElementById('distributor_id').value;
        const infoDiv = document.getElementById('distributor-info');
        
        if (distributorId && distributors[distributorId]) {
            const distributor = distributors[distributorId];
            let html = `
                <p class="mb-1"><strong>${distributor.name}</strong></p>
                <p class="mb-1">Wilaya: ${distributor.wilaya}</p>
                <p class="mb-1">Téléphone: ${distributor.phone || 'N/A'}</p>
                <p class="mb-0">
                    <strong>Solde dû: ${distributor.total_due ? distributor.total_due.toLocaleString('fr-FR') + ' DA' : '0 DA'}</strong>
                </p>
            `;
            infoDiv.innerHTML = html;
        } else {
            infoDiv.innerHTML = '<p class="mb-0">Sélectionnez un distributeur pour voir ses informations</p>';
        }
    }
    
    document.getElementById('distributor_id').addEventListener('change', updateDistributorInfo);
    
    // Initialiser
    updateDistributorInfo();
</script>
@endpush