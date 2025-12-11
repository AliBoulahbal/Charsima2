@csrf

@if(isset($kiosk))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label">Nom du Kiosque *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" 
                   value="{{ old('name', $kiosk->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="owner_name" class="form-label">Nom du Gérant *</label>
            <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                   id="owner_name" name="owner_name" 
                   value="{{ old('owner_name', $kiosk->owner_name ?? '') }}" required>
            @error('owner_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="phone" class="form-label">Téléphone *</label>
            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                   id="phone" name="phone" 
                   value="{{ old('phone', $kiosk->phone ?? '') }}" required>
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" 
                   value="{{ old('email', $kiosk->email ?? '') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="wilaya" class="form-label">Wilaya *</label>
            <select class="form-select @error('wilaya') is-invalid @enderror" id="wilaya" name="wilaya" required>
                <option value="">Sélectionner une wilaya</option>
                @foreach($wilayas as $wilaya)
                <option value="{{ $wilaya }}" 
                        {{ old('wilaya', $kiosk->wilaya ?? '') == $wilaya ? 'selected' : '' }}>
                    {{ $wilaya }}
                </option>
                @endforeach
            </select>
            @error('wilaya')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="district" class="form-label">Commune/District *</label>
            <input type="text" class="form-control @error('district') is-invalid @enderror" 
                   id="district" name="district" 
                   value="{{ old('district', $kiosk->district ?? '') }}" required>
            @error('district')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="user_id" class="form-label">Compte Utilisateur (Optionnel)</label>
            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                <option value="">Aucun compte lié</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" 
                        {{ old('user_id', $kiosk->user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
                @endforeach
            </select>
            @error('user_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="address" class="form-label">Adresse complète *</label>
    <textarea class="form-control @error('address') is-invalid @enderror" 
              id="address" name="address" rows="2" required>{{ old('address', $kiosk->address ?? '') }}</textarea>
    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="number" step="0.000001" class="form-control @error('latitude') is-invalid @enderror" 
                   id="latitude" name="latitude" 
                   value="{{ old('latitude', $kiosk->latitude ?? '') }}">
            @error('latitude')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="number" step="0.000001" class="form-control @error('longitude') is-invalid @enderror" 
                   id="longitude" name="longitude" 
                   value="{{ old('longitude', $kiosk->longitude ?? '') }}">
            @error('longitude')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
               value="1" {{ old('is_active', $kiosk->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Kiosque Actif (Permet les enregistrements de ventes)</label>
    </div>
</div>

<div class="d-flex justify-content-between">
    <a href="{{ route('admin.kiosks.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Annuler
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> 
        @if(isset($kiosk))
            Mettre à jour le Kiosque
        @else
            Créer le Kiosque
        @endif
    </button>
</div>