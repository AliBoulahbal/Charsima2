@csrf

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nom complet *</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">
            Mot de passe {{ isset($user) ? '(laisser vide pour ne pas changer)' : '*' }}
        </label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" {{ isset($user) ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="password_confirmation" class="form-label">
            Confirmation du mot de passe {{ isset($user) ? '' : '*' }}
        </label>
        <input type="password" class="form-control" 
               id="password_confirmation" name="password_confirmation" 
               {{ isset($user) ? '' : 'required' }}>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="role" class="form-label">Rôle *</label>
        <select class="form-select @error('role') is-invalid @enderror" 
                id="role" name="role" required>
            <option value="">Sélectionner un rôle</option>
            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>
                Administrateur
            </option>
            <option value="manager" {{ old('role', $user->role ?? '') == 'manager' ? 'selected' : '' }}>
                Manager
            </option>
            <option value="distributor" {{ old('role', $user->role ?? '') == 'distributor' ? 'selected' : '' }}>
                Distributeur
            </option>
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="phone" class="form-label">Téléphone</label>
        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
               id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="wilaya" class="form-label">Wilaya</label>
        <select class="form-select @error('wilaya') is-invalid @enderror" 
                id="wilaya" name="wilaya">
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
                    {{ old('wilaya', $user->wilaya ?? '') == $wilaya ? 'selected' : '' }}>
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
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> {{ isset($user) ? 'Mettre à jour' : 'Créer' }}
    </button>
</div>