@extends('layouts.app')

@section('title', 'Modifier la pièce')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier la pièce</h1>
        <a href="{{ route('pieces.show', $piece) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('pieces.update', $piece) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Informations du véhicule (lecture seule) -->
                        <div class="card mb-4 bg-light">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-car me-2"></i>Véhicule associé</h6>
                                <div class="d-flex align-items-center">
                                    @if($vehicle->photo_principale)
                                        <img src="{{ asset('storage/' . $vehicle->photo_principale) }}"
                                             class="rounded me-3" width="80" height="80" style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 80px; height: 80px;">
                                            <i class="fas fa-car fa-2x text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-0">{{ $vehicle->marque }} {{ $vehicle->modele }}</h5>
                                        <p class="text-muted mb-0">
                                            {{ $vehicle->annee }} • {{ $vehicle->numero_plaque }} • {{ ucfirst($vehicle->carburant) }}
                                        </p>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle"></i> Le véhicule associé ne peut pas être modifié
                                </small>
                            </div>
                        </div>

                        <!-- Informations de la pièce -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                           id="nom" name="nom" required value="{{ old('nom', $piece->nom) }}">
                                    @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference_constructeur" class="form-label">Référence constructeur</label>
                                    <input type="text" class="form-control @error('reference_constructeur') is-invalid @enderror"
                                           id="reference_constructeur" name="reference_constructeur"
                                           value="{{ old('reference_constructeur', $piece->reference_constructeur) }}">
                                    @error('reference_constructeur')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Champ Ville -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ville" class="form-label">Ville *</label>
                                    <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                           id="ville" name="ville" required
                                           value="{{ old('ville', $piece->ville ?? (Auth::user()->casse->ville ?? '')) }}"
                                           placeholder="Ex: Lomé, Sokodé...">
                                    @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" required>{{ old('description', $piece->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="prix" class="form-label">Prix (FCFA) *</label>
                                    <input type="number" step="0.01" class="form-control @error('prix') is-invalid @enderror"
                                           id="prix" name="prix" required value="{{ old('prix', $piece->prix) }}">
                                    @error('prix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="quantite" class="form-label">Quantité *</label>
                                    <input type="number" class="form-control @error('quantite') is-invalid @enderror"
                                           id="quantite" name="quantite" required value="{{ old('quantite', $piece->quantite) }}">
                                    @error('quantite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="etat" class="form-label">État *</label>
                                    <select class="form-select @error('etat') is-invalid @enderror" id="etat" name="etat" required>
                                        <option value="neuf" {{ old('etat', $piece->etat) == 'neuf' ? 'selected' : '' }}>Neuf</option>
                                        <option value="tres_bon" {{ old('etat', $piece->etat) == 'tres_bon' ? 'selected' : '' }}>Très bon</option>
                                        <option value="bon" {{ old('etat', $piece->etat) == 'bon' ? 'selected' : '' }}>Bon</option>
                                        <option value="moyen" {{ old('etat', $piece->etat) == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                        <option value="usage" {{ old('etat', $piece->etat) == 'usage' ? 'selected' : '' }}>Usage</option>
                                    </select>
                                    @error('etat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="compatible_avec" class="form-label">Compatible avec (modèles)</label>
                            <input type="text" class="form-control @error('compatible_avec') is-invalid @enderror"
                                   id="compatible_avec" name="compatible_avec"
                                   placeholder="Séparer par des virgules" value="{{ old('compatible_avec', $piece->compatible_avec ?? '') }}">
                            @error('compatible_avec')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Liste des modèles compatibles séparés par des virgules</div>
                        </div>

                        <div class="mb-3">
                            <label for="photos" class="form-label">Photos</label>
                            <input type="file" class="form-control @error('photos.*') is-invalid @enderror"
                                   id="photos" name="photos[]" multiple accept="image/*">
                            @error('photos.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($piece->photos && count($piece->photos) > 0)
                                <div class="mt-2">
                                    @foreach($piece->photos as $photo)
                                        <img src="{{ asset('storage/' . $photo) }}" width="80" class="rounded me-2">
                                    @endforeach
                                    <div class="form-text">Photos actuelles (seront remplacées si vous uploadez de nouvelles photos)</div>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="disponible" name="disponible" value="1"
                                {{ old('disponible', $piece->disponible) ? 'checked' : '' }}>
                            <label class="form-check-label" for="disponible">Disponible à la vente</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pieces.show', $piece) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Vous ne pouvez pas changer le véhicule associé</li>
                        <li>Les nouvelles photos remplaceront les anciennes</li>
                        <li>Vérifiez la disponibilité selon votre stock</li>
                    </ul>
                </div>
            </div>

            <!-- Statistiques de la pièce -->
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistiques</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Créée le :</strong> {{ $piece->created_at->format('d/m/Y') }}</p>
                    <p class="mb-0"><strong>Dernière mise à jour :</strong> {{ $piece->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
