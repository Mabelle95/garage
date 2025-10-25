@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('title', 'Modifier le profil')

@section('content')

    @php
        $user = Auth::user()
    @endphp

    @if (!$user->isCompleted())
        <div class="d-flex alert alert-danger" role="alert">
            {{-- icon --}}
            <p class="m-0">
                Veuillez completer votre profile
            </p>
        </div>
    @endif

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Modifier le profil</h1>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <h5 class="mb-3">Informations personnelles</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom complet *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" required
                                               value="{{ old('name', auth()->user()->name) }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" required
                                               value="{{ old('email', auth()->user()->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                                               id="telephone" name="telephone"
                                               value="{{ old('telephone', auth()->user()->telephone) }}">
                                        @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <h5>Information de paiement</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="flooz_number" class="form-label">Numero Flooz</label>
                                            <input type="number"
                                                   class="form-control @error('flooz_number') is-invalid @enderror"
                                                   id="flooz_number"
                                                   name="flooz_number"
                                                   pattern="[0-9]{8}"
                                                   minlength="8"
                                                   maxlength="8"
                                                   placeholder="Ex: 90123456"
                                                   value="{{ old('flooz_number', auth()->user()->flooz_number) }}">
                                            <div class="form-text">8 chiffres requis</div>
                                            @error('flooz_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mixx_number" class="form-label">Numero Mixx</label>
                                            <input type="number"
                                                   class="form-control @error('mixx_number') is-invalid @enderror"
                                                   id="mixx_number"
                                                   name="mixx_number"
                                                   pattern="[0-9]{8}"
                                                   minlength="8"
                                                   maxlength="8"
                                                   placeholder="Ex: 92123456"
                                                   value="{{ old('mixx_number', auth()->user()->mixx_number) }}">
                                            <div class="form-text">8 chiffres requis</div>
                                            @error('mixx_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="adresse" class="form-label">Adresse</label>
                                        <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                               id="adresse" name="adresse"
                                               value="{{ old('adresse', auth()->user()->adresse) }}">
                                        @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="code_postal" class="form-label">Code postal</label>
                                        <input type="text" class="form-control @error('code_postal') is-invalid @enderror"
                                               id="code_postal" name="code_postal"
                                               value="{{ old('code_postal', auth()->user()->code_postal) }}">
                                        @error('code_postal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="ville" class="form-label">Ville</label>
                                        <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                               id="ville" name="ville"
                                               value="{{ old('ville', auth()->user()->ville) }}">
                                        @error('ville')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->isCasse())
                                <hr>
                                <h5 class="mb-3">Informations professionnelles</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nom_entreprise" class="form-label">Nom de l'entreprise *</label>
                                            <input type="text" class="form-control @error('nom_entreprise') is-invalid @enderror"
                                                   id="nom_entreprise" name="nom_entreprise"
                                                   required value="{{ old('nom_entreprise', auth()->user()->nom_entreprise) }}">
                                            @error('nom_entreprise')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="siret" class="form-label">SIRET</label>
                                            <input type="text" class="form-control @error('siret') is-invalid @enderror"
                                                   id="siret" name="siret"
                                                   value="{{ old('siret', auth()->user()->siret) }}">
                                            @error('siret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description"
                                              rows="3">{{ old('description', auth()->user()->description) }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                           id="logo" name="logo" accept="image/*">
                                    @if(auth()->user()->logo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . auth()->user()->logo) }}" width="100" class="rounded">
                                            <div class="form-text">Logo actuel</div>
                                        </div>
                                    @endif
                                    @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Localisation GPS</label>
                                    <button type="button" class="btn btn-info btn-sm d-block mb-2" id="getLocationBtn">
                                        <i class="fas fa-map-marker-alt"></i> Obtenir ma position actuelle
                                    </button>
                                    <div id="locationStatus" class="text-muted small mb-2" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i> Récupération de la position...
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">Latitude</label>
                                            <input type="number" step="any"
                                                   class="form-control @error('latitude') is-invalid @enderror"
                                                   id="latitude" name="latitude"
                                                   readonly
                                                   placeholder="Ex: 6.1256"
                                                   value="{{ old('latitude', auth()->user()->latitude) }}">
                                            <div class="form-text">Utilisez le bouton ci-dessus pour obtenir votre position</div>
                                            @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">Longitude</label>
                                            <input type="number" step="any"
                                                   class="form-control @error('longitude') is-invalid @enderror"
                                                   id="longitude" name="longitude"
                                                   readonly
                                                   placeholder="Ex: 1.2221"
                                                   value="{{ old('longitude', auth()->user()->longitude) }}">
                                            <div class="form-text">Utilisez le bouton ci-dessus pour obtenir votre position</div>
                                            @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Changer le mot de passe -->
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0">Changer le mot de passe</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control" id="current_password" name="current_password"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-key"></i> Changer le mot de passe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validation JavaScript supplémentaire pour les numéros
        document.addEventListener('DOMContentLoaded', function() {
            const floozInput = document.getElementById('flooz_number');
            const mixxInput = document.getElementById('mixx_number');

            function validatePhoneNumber(input) {
                input.addEventListener('input', function() {
                    // Supprimer tous les caractères non numériques
                    this.value = this.value.replace(/[^0-9]/g, '');

                    // Limiter à 8 chiffres
                    if (this.value.length > 8) {
                        this.value = this.value.slice(0, 8);
                    }

                    // Validation visuelle
                    if (this.value.length === 8 || this.value.length === 0) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (this.value.length > 0) {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            if (floozInput) validatePhoneNumber(floozInput);
            if (mixxInput) validatePhoneNumber(mixxInput);

            // Géolocalisation GPS
            const getLocationBtn = document.getElementById('getLocationBtn');
            const locationStatus = document.getElementById('locationStatus');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            if (getLocationBtn) {
                getLocationBtn.addEventListener('click', function() {
                    // Vérifier si la géolocalisation est supportée
                    if (!navigator.geolocation) {
                        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
                        return;
                    }

                    // Afficher le statut de chargement
                    locationStatus.style.display = 'block';
                    getLocationBtn.disabled = true;
                    getLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Récupération...';

                    // Options de géolocalisation
                    const options = {
                        enableHighAccuracy: true, // Haute précision
                        timeout: 10000, // Timeout de 10 secondes
                        maximumAge: 0 // Ne pas utiliser de cache
                    };

                    // Récupérer la position
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            // Succès
                            const latitude = position.coords.latitude;
                            const longitude = position.coords.longitude;
                            const accuracy = position.coords.accuracy;

                            // Remplir les champs
                            latitudeInput.value = latitude.toFixed(6);
                            longitudeInput.value = longitude.toFixed(6);

                            // Ajouter la classe de succès
                            latitudeInput.classList.add('is-valid');
                            longitudeInput.classList.add('is-valid');

                            // Afficher le message de succès
                            locationStatus.innerHTML = `
                                <i class="fas fa-check-circle text-success"></i>
                                Position obtenue avec succès ! (Précision: ${Math.round(accuracy)}m)
                            `;
                            locationStatus.className = 'alert alert-success small mb-2';

                            // Réinitialiser le bouton
                            getLocationBtn.disabled = false;
                            getLocationBtn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Obtenir ma position actuelle';

                            // Masquer le message après 5 secondes
                            setTimeout(function() {
                                locationStatus.style.display = 'none';
                            }, 5000);
                        },
                        function(error) {
                            // Erreur
                            let errorMessage = '';

                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage = 'Vous avez refusé l\'accès à votre position. Veuillez autoriser la géolocalisation dans les paramètres de votre navigateur.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage = 'Les informations de localisation ne sont pas disponibles.';
                                    break;
                                case error.TIMEOUT:
                                    errorMessage = 'La demande de géolocalisation a expiré. Veuillez réessayer.';
                                    break;
                                default:
                                    errorMessage = 'Une erreur inconnue s\'est produite lors de la géolocalisation.';
                                    break;
                            }

                            locationStatus.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errorMessage}`;
                            locationStatus.className = 'alert alert-danger small mb-2';
                            locationStatus.style.display = 'block';

                            // Réinitialiser le bouton
                            getLocationBtn.disabled = false;
                            getLocationBtn.innerHTML = '<i class="fas fa-map-marker-alt"></i> Réessayer';
                        },
                        options
                    );
                });
            }
        });
    </script>
@endsection
