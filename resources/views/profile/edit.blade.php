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
                                    value="{{ old('name', auth()->user()->name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="{{ old('email', auth()->user()->email) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone"
                                    value="{{ old('telephone', auth()->user()->telephone) }}">
                            </div>
                        </div>

                        @if ($user->role->value === 'casse')
                            <div class="mb-3">
                                <h5>Information de paiement</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="flooz_number" class="form-label">Numéro Flooz</label>
                                        <input type="text" class="form-control" id="flooz_number" name="flooz_number"
                                            value="{{ old('flooz_number', auth()->user()->flooz_number) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mixx_number" class="form-label">Numéro Mixx</label>
                                        <input type="text" class="form-control" id="mixx_number" name="mixx_number"
                                            value="{{ old('mixx_number', auth()->user()->mixx_number) }}">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse"
                                    value="{{ old('adresse', auth()->user()->adresse) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="code_postal" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="code_postal" name="code_postal"
                                    value="{{ old('code_postal', auth()->user()->code_postal) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="ville" name="ville"
                                    value="{{ old('ville', auth()->user()->ville) }}">
                            </div>
                        </div>

                        @if (auth()->user()->isCasse())
                            <hr>
                            <h5 class="mb-3">Informations professionnelles</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nom_entreprise" class="form-label">Nom de l'entreprise *</label>
                                    <input type="text" class="form-control" id="nom_entreprise" name="nom_entreprise"
                                        required value="{{ old('nom_entreprise', auth()->user()->nom_entreprise) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="siret" class="form-label">SIRET</label>
                                    <input type="text" class="form-control" id="siret" name="siret"
                                        value="{{ old('siret', auth()->user()->siret) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', auth()->user()->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                @if (auth()->user()->logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . auth()->user()->logo) }}" width="100" class="rounded">
                                        <div class="form-text">Logo actuel</div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="number" step="any" class="form-control" id="latitude" name="latitude"
                                        value="{{ old('latitude', auth()->user()->latitude) }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="number" step="any" class="form-control" id="longitude" name="longitude"
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

{{-- Script de géolocalisation automatique --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    document.getElementById("latitude").value = position.coords.latitude;
                    document.getElementById("longitude").value = position.coords.longitude;
                },
                (error) => {
                    console.warn("Impossible d’obtenir la position :", error.message);
                }
            );
        } else {
            console.warn("La géolocalisation n’est pas supportée par ce navigateur.");
        }
    });
</script>


@endsection
