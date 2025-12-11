@csrf

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="user_id" class="form-label">Utilisateur associé *</label>
        <select class="form-select @error('user_id') is-invalid @enderror" 
                id="user_id" name="user_id" required>
            <option value="">Sélectionner un utilisateur</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" 
                    {{ old('user_id', $distributor->user_id ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->email }})
            </option>
            @endforeach
        </select>
        @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">
            Seuls les utilisateurs avec le rôle "distributeur" et sans profil distributeur existant apparaissent ici.
        </small>
    </div>

    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nom du distributeur *</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name', $distributor->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="phone" class="form-label">Téléphone</label>
        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
               id="phone" name="phone" value="{{ old('phone', $distributor->phone ?? '') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="wilaya" class="form-label">Wilaya *</label>
        <select class="form-select @error('wilaya') is-invalid @enderror" 
                id="wilaya" name="wilaya" required>
            <option value="">Sélectionner une wilaya</option>
            @foreach([
                'Adrar', 'Chlef', 'Laghouat', 'Oum El Bouaghi', 'Batna', 'Béjaïa', 'Biskra', 'Béchar', 
                'Blida', 'Bouira', 'Tamanrasset', 'Tébessa', 'Tlemcen', 'Tiaret', 'Tizi Ouzou', 'Alger',
                'Djelfa', 'Jijel', 'Sétif', 'Saïda', 'Skikda', 'Sidi Bel Abbès', 'Annaba', 'Guelma',
                'Constantine', 'Médéa', 'Mostaganem', 'M\'Sila', 'Mascara', 'Ouargla', 'Oran', 'El Bayadh',
                'Illizi', 'Bordj Bou Arréridj', 'Boumerdès', 'El Tarf', 'Tindouf', 'Tissemsilt', 'El Oued',
                'Khenchela', 'Souk Ahras', 'Tipaza', 'Mila', 'Aïn Defla', 'Naâma', 'Aïn Témouchent',
                'Ghardaïa', 'Relizane', 'Timimoun', 'Bordj Badji Mokhtar', 'Ouled Djellal', 'Béni Abbès',
                'In Salah', 'In Guezzam', 'Touggourt', 'Djanet', 'El M\'Ghair', 'El Meniaa'
            ] as $wilaya)
            <option value="{{ $wilaya }}" 
                    {{ old('wilaya', $distributor->wilaya ?? '') == $wilaya ? 'selected' : '' }}>
                {{ $wilaya }}
            </option>
            @endforeach
        </select>
        @error('wilaya')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('admin.distributors.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> {{ isset($distributor) ? 'Mettre à jour' : 'Créer' }}
    </button>
</div>