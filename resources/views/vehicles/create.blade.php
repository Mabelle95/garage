@extends('layouts.app')

@section('title', 'Ajouter un véhicule')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Ajouter un véhicule</h1>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="marque" class="form-label">Marque *</label>
                                <input type="text" class="form-control" id="marque" name="marque" required value="{{ old('marque') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modele" class="form-label">Modèle *</label>
                                <input type="text" class="form-control" id="modele" name="modele" required value="{{ old('modele') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="annee" class="form-label">Année *</label>
                                <input type="number" class="form-control" id="annee" name="annee" required value="{{ old('annee') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numero_plaque" class="form-label">Numéro de plaque *</label>
                                <input type="text" class="form-control" id="numero_plaque" name="numero_plaque" required value="{{ old('numero_plaque') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="couleur" class="form-label">Couleur *</label>
                                <input type="text" class="form-control" id="couleur" name="couleur" required value="{{ old('couleur') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="carburant" class="form-label">Carburant *</label>
                                <select class="form-select" id="carburant" name="carburant" required>
                                    <option value="essence">Essence</option>
                                    <option value="diesel">Diesel</option>
                                    <option value="hybride">Hybride</option>
                                    <option value="electrique">Électrique</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="transmission" class="form-label">Transmission *</label>
                                <select class="form-select" id="transmission" name="transmission" required>
                                    <option value="manuelle">Manuelle</option>
                                    <option value="automatique">Automatique</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="kilometrage" class="form-label">Kilométrage *</label>
                                <input type="number" class="form-control" id="kilometrage" name="kilometrage" required value="{{ old('kilometrage') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="etat" class="form-label">État *</label>
                                <select class="form-select" id="etat" name="etat" required>
                                    <option value="bon">Bon</option>
                                    <option value="moyen">Moyen</option>
                                    <option value="mauvais">Mauvais</option>
                                    <option value="epave">Épave</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="prix_epave" class="form-label">Prix de vente (FCFA) *</label>
                                <input type="number" step="0.01" class="form-control" id="prix_epave" name="prix_epave" required value="{{ old('prix_epave') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photo_principale" class="form-label">Photo principale</label>
                        <input type="file" class="form-control" id="photo_principale" name="photo_principale" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="photos_additionnelles" class="form-label">Photos additionnelles</label>
                        <input type="file" class="form-control" id="photos_additionnelles" name="photos_additionnelles[]" multiple accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer le véhicule
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
