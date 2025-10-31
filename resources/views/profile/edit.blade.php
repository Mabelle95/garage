@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('title', 'Modifier le profil')

@section('content')
    @php
        $user = Auth::user();
    @endphp


    @if (!$user->isCompleted())
        <div class="d-flex alert alert-danger" role="alert">
            <p class="m-0">Veuillez completer votre profil</p>
        </div>
    @endif

    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h1 class="mb-2">Modifier le profil</h1>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary mb-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="row gy-4">
            <div class="col-lg-8 col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <h5 class="mb-3">Informations personnelles</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom complet *</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           maxlength="100"
                                           value="{{ old('name', auth()->user()->name) }}">
                                    <div class="form-text">Maximum 100 caractères</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           maxlength="100"
                                           value="{{ old('email', auth()->user()->email) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" id="telephone" name="telephone"
                                           pattern="[0-9]{8}"
                                           maxlength="8"
                                           title="Le numéro de téléphone doit contenir exactement 8 chiffres"
                                           placeholder="Ex: 90123456"
                                           value="{{ old('telephone', auth()->user()->telephone) }}">
                                    <div class="form-text">Exactement 8 chiffres</div>
                                </div>
                            </div>

                            @if ($user->role->value === 'casse')
                                <div class="mb-3">
                                    <h5>Information de paiement</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="flooz_number" class="form-label">Numéro Flooz</label>
                                            <input type="text" class="form-control" id="flooz_number" name="flooz_number"
                                                   pattern="[0-9]{8}"
                                                   maxlength="8"
                                                   title="Le numéro Flooz doit contenir exactement 8 chiffres"
                                                   placeholder="Ex: 90123456"
                                                   value="{{ old('flooz_number', auth()->user()->flooz_number) }}">
                                            <div class="form-text">Exactement 8 chiffres</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mixx_number" class="form-label">Numéro Mixx</label>
                                            <input type="text" class="form-control" id="mixx_number" name="mixx_number"
                                                   pattern="[0-9]{8}"
                                                   maxlength="8"
                                                   title="Le numéro Mixx doit contenir exactement 8 chiffres"
                                                   placeholder="Ex: 90123456"
                                                   value="{{ old('mixx_number', auth()->user()->mixx_number) }}">
                                            <div class="form-text">Exactement 8 chiffres</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse"
                                           maxlength="200"
                                           value="{{ old('adresse', auth()->user()->adresse) }}">
                                    <div class="form-text">Maximum 200 caractères</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="code_postal" class="form-label">Code postal</label>
                                    <input type="text" class="form-control" id="code_postal" name="code_postal"
                                           pattern="[0-9]{3}"
                                           maxlength="5"
                                           title="Le code postal doit contenir exactement 5 chiffres"
                                           placeholder="Ex: 001"
                                           value="{{ old('code_postal', auth()->user()->code_postal) }}">
                                    <div class="form-text">5 chiffres</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ville" class="form-label">Ville</label>
                                    <input type="text" class="form-control" id="ville" name="ville"
                                           maxlength="100"
                                           value="{{ old('ville', auth()->user()->ville) }}">
                                    <div class="form-text">Maximum 100 caractères</div>
                                </div>
                            </div>

                            @if (auth()->user()->isCasse())
                                <hr>
                                <h5 class="mb-3">Informations professionnelles</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nom_entreprise" class="form-label">Nom de l'entreprise *</label>
                                        <input type="text" class="form-control" id="nom_entreprise" name="nom_entreprise"
                                               required
                                               maxlength="150"
                                               value="{{ old('nom_entreprise', auth()->user()->nom_entreprise) }}">
                                        <div class="form-text">Maximum 150 caractères</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="siret" class="form-label">SIRET</label>
                                        <input type="text" class="form-control" id="siret" name="siret"
                                               pattern="[0-9]{14}"
                                               maxlength="14"
                                               title="Le SIRET doit contenir exactement 14 chiffres"
                                               placeholder="Ex: 12345678901234"
                                               value="{{ old('siret', auth()->user()->siret) }}">
                                        <div class="form-text">14 chiffres</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                              maxlength="1000">{{ old('description', auth()->user()->description) }}</textarea>
                                    <div class="form-text">Maximum 1000 caractères</div>
                                </div>

                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <input type="file" class="form-control" id="logo" name="logo"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    @if (auth()->user()->logo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . auth()->user()->logo) }}" width="100" class="rounded">
                                            <div class="form-text">Logo actuel</div>
                                        </div>
                                    @endif
                                    <div class="form-text">Formats acceptés: JPG, PNG, GIF, WEBP (max 2MB)</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="number" step="any" class="form-control" id="latitude" name="latitude"
                                               min="-90" max="90"
                                               value="{{ old('latitude', auth()->user()->latitude) }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="number" step="any" class="form-control" id="longitude" name="longitude"
                                               min="-180" max="180"
                                               value="{{ old('longitude', auth()->user()->longitude) }}" readonly>
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

            <div class="col-lg-4 col-md-12">
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
                                       required
                                       minlength="8">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                       minlength="8"
                                       title="Le mot de passe doit contenir au moins 8 caractères">
                                <div class="form-text">Minimum 8 caractères</div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" required
                                       minlength="8">
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

    {{-- Script de géolocalisation automatique et validation numéros --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Géolocalisation automatique
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        document.getElementById("latitude").value = position.coords.latitude;
                        document.getElementById("longitude").value = position.coords.longitude;
                    },
                    (error) => {
                        console.warn("Impossible d'obtenir la position :", error.message);
                    }
                );
            } else {
                console.warn("La géolocalisation n'est pas supportée par ce navigateur.");
            }

            // Validation stricte des champs numériques (empêche la saisie de caractères non-numériques)
            const numericFields = ['telephone', 'flooz_number', 'mixx_number', 'code_postal', 'siret'];

            numericFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function(e) {
                        // Supprime tous les caractères non-numériques
                        this.value = this.value.replace(/[^0-9]/g, '');

                        // Limite la longueur selon le champ
                        const maxLength = this.getAttribute('maxlength');
                        if (maxLength && this.value.length > maxLength) {
                            this.value = this.value.slice(0, maxLength);
                        }
                    });

                    // Empêche le collage de texte non-numérique
                    field.addEventListener('paste', function(e) {
                        e.preventDefault();
                        const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                        const numericOnly = pasteData.replace(/[^0-9]/g, '');
                        const maxLength = this.getAttribute('maxlength');
                        this.value = maxLength ? numericOnly.slice(0, maxLength) : numericOnly;
                    });
                }
            });

            // Validation de la taille du fichier logo (2MB max)
            const logoInput = document.getElementById('logo');
            if (logoInput) {
                logoInput.addEventListener('change', function(e) {
                    const file = this.files[0];
                    if (file) {
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        if (file.size > maxSize) {
                            alert('Le fichier est trop volumineux. Taille maximale: 2MB');
                            this.value = '';
                        }
                    }
                });
            }
        });
    </script>


@endsection
