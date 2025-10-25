@extends('layouts.app')

@section('title', 'Modifier le véhicule')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Modifier le véhicule</h1>
            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="marque" class="form-label">Marque *</label>
                                <input type="text" class="form-control" id="marque" name="marque" required value="{{ old('marque', $vehicle->marque) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modele" class="form-label">Modèle *</label>
                                <input type="text" class="form-control" id="modele" name="modele" required value="{{ old('modele', $vehicle->modele) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="annee" class="form-label">Année *</label>
                                <input type="number" class="form-control" id="annee" name="annee" required
                                       min="1900" max="{{ date('Y') + 1 }}" value="{{ old('annee', $vehicle->annee) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="couleur" class="form-label">Couleur *</label>
                                <input type="text" class="form-control" id="couleur" name="couleur" required value="{{ old('couleur', $vehicle->couleur) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="carburant" class="form-label">Carburant *</label>
                                <select class="form-select" id="carburant" name="carburant" required>
                                    <option value="essence" {{ old('carburant', $vehicle->carburant) == 'essence' ? 'selected' : '' }}>Essence</option>
                                    <option value="diesel" {{ old('carburant', $vehicle->carburant) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="hybride" {{ old('carburant', $vehicle->carburant) == 'hybride' ? 'selected' : '' }}>Hybride</option>
                                    <option value="electrique" {{ old('carburant', $vehicle->carburant) == 'electrique' ? 'selected' : '' }}>Électrique</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="transmission" class="form-label">Transmission *</label>
                                <select class="form-select" id="transmission" name="transmission" required>
                                    <option value="manuelle" {{ old('transmission', $vehicle->transmission) == 'manuelle' ? 'selected' : '' }}>Manuelle</option>
                                    <option value="automatique" {{ old('transmission', $vehicle->transmission) == 'automatique' ? 'selected' : '' }}>Automatique</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="kilometrage" class="form-label">Kilométrage *</label>
                                <input type="number" class="form-control" id="kilometrage" name="kilometrage" required
                                       min="0" value="{{ old('kilometrage', $vehicle->kilometrage) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="etat" class="form-label">État *</label>
                                <select class="form-select" id="etat" name="etat" required>
                                    <option value="bon" {{ old('etat', $vehicle->etat) == 'bon' ? 'selected' : '' }}>Bon</option>
                                    <option value="moyen" {{ old('etat', $vehicle->etat) == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                    <option value="mauvais" {{ old('etat', $vehicle->etat) == 'mauvais' ? 'selected' : '' }}>Mauvais</option>
                                    <option value="epave" {{ old('etat', $vehicle->etat) == 'epave' ? 'selected' : '' }}>Épave</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prix_epave" class="form-label">Prix de vente (FCFA) *</label>
                                <input type="number" step="0.01" class="form-control" id="prix_epave" name="prix_epave" required value="{{ old('prix_epave', $vehicle->prix_epave) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vendu" class="form-label">Statut</label>
                                <select class="form-select" id="vendu" name="vendu">
                                    <option value="0" {{ old('vendu', $vehicle->vendu) == 0 ? 'selected' : '' }}>Disponible</option>
                                    <option value="1" {{ old('vendu', $vehicle->vendu) == 1 ? 'selected' : '' }}>Vendu</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photo_principale" class="form-label">Photo principale</label>
                        <input type="file" class="form-control" id="photo_principale" name="photo_principale" accept="image/*">
                        @if($vehicle->photo_principale)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $vehicle->photo_principale) }}" width="100" class="rounded">
                                <div class="form-text">Photo actuelle</div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="photos_additionnelles" class="form-label">Photos additionnelles</label>
                        <input type="file" class="form-control" id="photos_additionnelles" name="photos_additionnelles[]" multiple accept="image/*">
                        @if($vehicle->photos_additionnelles && count($vehicle->photos_additionnelles) > 0)
                            <div class="mt-2">
                                @foreach($vehicle->photos_additionnelles as $photo)
                                    <img src="{{ asset('storage/' . $photo) }}" width="80" class="rounded me-2">
                                @endforeach
                                <div class="form-text">Photos actuelles</div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $vehicle->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
