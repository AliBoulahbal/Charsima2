@csrf

<div class="row">
    <!-- School Name -->
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nom de l'école *</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name', $school->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Wilaya -->
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
                    {{ old('wilaya', $school->wilaya ?? '') == $wilaya ? 'selected' : '' }}>
                {{ $wilaya }}
            </option>
            @endforeach
        </select>
        @error('wilaya')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- ADD THIS NEW ROW FOR COMMUNE -->
<div class="row">
    <!-- Commune -->
    <div class="col-md-6 mb-3">
        <label for="commune" class="form-label">Commune / Village *</label>
        <input type="text" class="form-control @error('commune') is-invalid @enderror" 
               id="commune" name="commune" value="{{ old('commune', $school->commune ?? '') }}" required>
        @error('commune')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- District -->
    <div class="col-md-6 mb-3">
        <label for="district" class="form-label">District / Adresse</label>
        <input type="text" class="form-control @error('district') is-invalid @enderror" 
               id="district" name="district" value="{{ old('district', $school->district ?? '') }}">
        @error('district')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <!-- Address -->
    <div class="col-md-6 mb-3">
        <label for="address" class="form-label">Adresse complète</label>
        <input type="text" class="form-control @error('address') is-invalid @enderror" 
               id="address" name="address" value="{{ old('address', $school->address ?? '') }}">
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Phone -->
    <div class="col-md-6 mb-3">
        <label for="phone" class="form-label">Téléphone</label>
        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
               id="phone" name="phone" value="{{ old('phone', $school->phone ?? '') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <!-- Manager Name -->
    <div class="col-md-6 mb-3">
        <label for="manager_name" class="form-label">Nom du directeur</label>
        <input type="text" class="form-control @error('manager_name') is-invalid @enderror" 
               id="manager_name" name="manager_name" 
               value="{{ old('manager_name', $school->manager_name ?? '') }}">
        @error('manager_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Student Count -->
    <div class="col-md-6 mb-3">
        <label for="student_count" class="form-label">Nombre d'élèves</label>
        <input type="number" class="form-control @error('student_count') is-invalid @enderror" 
               id="student_count" name="student_count" 
               value="{{ old('student_count', $school->student_count ?? 0) }}" min="0">
        @error('student_count')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('admin.schools.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> {{ isset($school) ? 'Mettre à jour' : 'Créer' }}
    </button>
</div>