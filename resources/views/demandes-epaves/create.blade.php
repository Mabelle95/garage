@extends('layouts.app')

@section('title', 'Créer une demande d\'épave')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Vendre mon épave</h1>
            <a href="{{ route('demandes-epaves.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('demandes-epaves.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Informations du véhicule</h5>

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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="annee" class="form-label">Année *</label>
                                        <input type="number" class="form-control" id="annee" name="annee" required
                                               min="1900" max="{{ date('Y') + 1 }}" value="{{ old('annee') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                        <label for="kilometrage" class="form-label">Kilométrage *</label>
                                        <input type="number" class="form-control" id="kilometrage" name="kilometrage" required
                                               min="0" value="{{ old('kilometrage') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="etat" class="form-label">État du véhicule *</label>
                                <select class="form-select" id="etat" name="etat" required>
                                    <option value="bon">Bon état</option>
                                    <option value="moyen">État moyen</option>
                                    <option value="mauvais">Mauvais état</option>
                                    <option value="epave">Épave (accidenté)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="prix_souhaite" class="form-label">Prix souhaité (FCFA)</label>
                                <input type="number" step="0.01" class="form-control" id="prix_souhaite"
                                       name="prix_souhaite" value="{{ old('prix_souhaite') }}">
                                <div class="form-text">Laissez vide pour recevoir des offres</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Informations supplémentaires</h5>

                            <div class="mb-3">
                                <label for="numero_chassis" class="form-label">Numéro de chassis *</label>
                                <input type="text" class="form-control" id="numero_chassis" name="numero_chassis" required value="{{ old('numero_chassis') }}">
                            </div>

                            <div class="mb-3">
                                <label for="numero_plaque" class="form-label">Numéro de plaque *</label>
                                <input type="text" class="form-control" id="numero_plaque" name="numero_plaque" required value="{{ old('numero_plaque') }}">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                <div class="form-text">Décrivez l'état du véhicule, les dommages, etc.</div>
                            </div>

                            <div class="mb-3">
                                <label for="photos" class="form-label">Photos du véhicule</label>
                                <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                                <div class="form-text">Ajoutez des photos montrant l'état du véhicule</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Informations de contact</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone_contact" class="form-label">Téléphone de contact *</label>
                                <input type="text" class="form-control" id="telephone_contact" name="telephone_contact" required value="{{ old('telephone_contact') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse où se trouve le véhicule *</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required value="{{ old('adresse') }}">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Information importante</h6>
                        <p class="mb-0">
                            Votre demande sera visible par les casses professionnelles de votre région.
                            Elles pourront vous faire des offres pour l'achat de votre véhicule.
                        </p>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Publier la demande
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
