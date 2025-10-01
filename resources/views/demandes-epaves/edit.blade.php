@extends('layouts.app')

@section('title', 'Modifier la demande d\'épave')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Modifier la demande d'épave</h1>
            <a href="{{ route('demandes-epaves.show', $demandeEpave) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('demandes-epaves.update', $demandeEpave) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Informations du véhicule</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="marque" class="form-label">Marque *</label>
                                        <input type="text" class="form-control" id="marque" name="marque" required value="{{ old('marque', $demandeEpave->marque) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="modele" class="form-label">Modèle *</label>
                                        <input type="text" class="form-control" id="modele" name="modele" required value="{{ old('modele', $demandeEpave->modele) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="annee" class="form-label">Année *</label>
                                        <input type="number" class="form-control" id="annee" name="annee" required
                                               min="1900" max="{{ date('Y') + 1 }}" value="{{ old('annee', $demandeEpave->annee) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="couleur" class="form-label">Couleur *</label>
                                        <input type="text" class="form-control" id="couleur" name="couleur" required value="{{ old('couleur', $demandeEpave->couleur) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="carburant" class="form-label">Carburant *</label>
                                        <select class="form-select" id="carburant" name="carburant" required>
                                            <option value="essence" {{ old('carburant', $demandeEpave->carburant) == 'essence' ? 'selected' : '' }}>Essence</option>
                                            <option value="diesel" {{ old('carburant', $demandeEpave->carburant) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                            <option value="hybride" {{ old('carburant', $demandeEpave->carburant) == 'hybride' ? 'selected' : '' }}>Hybride</option>
                                            <option value="electrique" {{ old('carburant', $demandeEpave->carburant) == 'electrique' ? 'selected' : '' }}>Électrique</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kilometrage" class="form-label">Kilométrage *</label>
                                        <input type="number" class="form-control" id="kilometrage" name="kilometrage" required
                                               min="0" value="{{ old('kilometrage', $demandeEpave->kilometrage) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="etat" class="form-label">État du véhicule *</label>
                                <select class="form-select" id="etat" name="etat" required>
                                    <option value="bon" {{ old('etat', $demandeEpave->etat) == 'bon' ? 'selected' : '' }}>Bon état</option>
                                    <option value="moyen" {{ old('etat', $demandeEpave->etat) == 'moyen' ? 'selected' : '' }}>État moyen</option>
                                    <option value="mauvais" {{ old('etat', $demandeEpave->etat) == 'mauvais' ? 'selected' : '' }}>Mauvais état</option>
                                    <option value="epave" {{ old('etat', $demandeEpave->etat) == 'epave' ? 'selected' : '' }}>Épave (accidenté)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="prix_souhaite" class="form-label">Prix souhaité (FCFA)</label>
                                <input type="number" step="0.01" class="form-control" id="prix_souhaite"
                                       name="prix_souhaite" value="{{ old('prix_souhaite', $demandeEpave->prix_souhaite) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Informations supplémentaires</h5>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $demandeEpave->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="photos" class="form-label">Photos du véhicule</label>
                                <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                                @if($demandeEpave->photos && count($demandeEpave->photos) > 0)
                                    <div class="mt-2">
                                        @foreach($demandeEpave->photos as $photo)
                                            <img src="{{ asset('storage/' . $photo) }}" width="80" class="rounded me-2">
                                        @endforeach
                                        <div class="form-text">Photos actuelles</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Informations de contact</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone_contact" class="form-label">Téléphone de contact *</label>
                                <input type="text" class="form-control" id="telephone_contact" name="telephone_contact" required value="{{ old('telephone_contact', $demandeEpave->telephone_contact) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse où se trouve le véhicule *</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required value="{{ old('adresse', $demandeEpave->adresse) }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour la demande
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
