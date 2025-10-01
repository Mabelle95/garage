@extends('layouts.app')

@section('title', 'Ajouter une pièce détachée')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Ajouter une nouvelle pièce</h1>
        <a href="{{ route('pieces.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Informations complètes</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pieces.store') }}" method="POST" enctype="multipart/form-data" id="pieceForm">
                        @csrf

                        <!-- SECTION 1: Choix du véhicule -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-car me-2"></i>Véhicule associé</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Cette pièce provient de :</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="vehicle_option" id="existing_vehicle" value="existing" checked>
                                        <label class="form-check-label" for="existing_vehicle">
                                            Un véhicule existant
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="vehicle_option" id="new_vehicle" value="new">
                                        <label class="form-check-label" for="new_vehicle">
                                            Un nouveau véhicule (créer maintenant)
                                        </label>
                                    </div>
                                </div>

                                <!-- Sélection véhicule existant -->
                                <div id="existing_vehicle_section">
                                    <div class="mb-3">
                                        <label for="vehicle_id" class="form-label required">Sélectionner le véhicule</label>
                                        <select class="form-select @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id">
                                            <option value="">-- Choisir un véhicule --</option>
                                            @foreach($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                    {{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->annee }}) - {{ $vehicle->numero_plaque }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vehicle_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Création nouveau véhicule -->
                                <div id="new_vehicle_section" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Remplissez les informations du véhicule ci-dessous
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="marque" class="form-label required">Marque</label>
                                                <input type="text" class="form-control @error('marque') is-invalid @enderror"
                                                       id="marque" name="marque" value="{{ old('marque') }}">
                                                @error('marque')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="modele" class="form-label required">Modèle</label>
                                                <input type="text" class="form-control @error('modele') is-invalid @enderror"
                                                       id="modele" name="modele" value="{{ old('modele') }}">
                                                @error('modele')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="annee" class="form-label required">Année</label>
                                                <input type="number" class="form-control @error('annee') is-invalid @enderror"
                                                       id="annee" name="annee" min="1900" max="{{ date('Y') + 1 }}" value="{{ old('annee') }}">
                                                @error('annee')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="numero_plaque" class="form-label required">Numéro de plaque</label>
                                                <input type="text" class="form-control @error('numero_plaque') is-invalid @enderror"
                                                       id="numero_plaque" name="numero_plaque" value="{{ old('numero_plaque') }}">
                                                @error('numero_plaque')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="couleur" class="form-label required">Couleur</label>
                                                <input type="text" class="form-control @error('couleur') is-invalid @enderror"
                                                       id="couleur" name="couleur" value="{{ old('couleur') }}">
                                                @error('couleur')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="carburant" class="form-label required">Carburant</label>
                                                <select class="form-select @error('carburant') is-invalid @enderror" id="carburant" name="carburant">
                                                    <option value="">-- Sélectionner --</option>
                                                    <option value="essence" {{ old('carburant') == 'essence' ? 'selected' : '' }}>Essence</option>
                                                    <option value="diesel" {{ old('carburant') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                                    <option value="hybride" {{ old('carburant') == 'hybride' ? 'selected' : '' }}>Hybride</option>
                                                    <option value="electrique" {{ old('carburant') == 'electrique' ? 'selected' : '' }}>Électrique</option>
                                                </select>
                                                @error('carburant')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="transmission" class="form-label required">Transmission</label>
                                                <select class="form-select @error('transmission') is-invalid @enderror" id="transmission" name="transmission">
                                                    <option value="">-- Sélectionner --</option>
                                                    <option value="manuelle" {{ old('transmission') == 'manuelle' ? 'selected' : '' }}>Manuelle</option>
                                                    <option value="automatique" {{ old('transmission') == 'automatique' ? 'selected' : '' }}>Automatique</option>
                                                </select>
                                                @error('transmission')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="kilometrage" class="form-label required">Kilométrage</label>
                                                <input type="number" class="form-control @error('kilometrage') is-invalid @enderror"
                                                       id="kilometrage" name="kilometrage" min="0" value="{{ old('kilometrage') }}">
                                                @error('kilometrage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="etat" class="form-label required">État</label>
                                                <select class="form-select @error('etat') is-invalid @enderror" id="etat" name="etat">
                                                    <option value="">-- Sélectionner --</option>
                                                    <option value="bon" {{ old('etat') == 'bon' ? 'selected' : '' }}>Bon</option>
                                                    <option value="moyen" {{ old('etat') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                                    <option value="mauvais" {{ old('etat') == 'mauvais' ? 'selected' : '' }}>Mauvais</option>
                                                    <option value="epave" {{ old('etat') == 'epave' ? 'selected' : '' }}>Épave</option>
                                                </select>
                                                @error('etat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="prix_epave" class="form-label required">Prix du véhicule (FCFA)</label>
                                                <input type="number" step="0.01" class="form-control @error('prix_epave') is-invalid @enderror"
                                                       id="prix_epave" name="prix_epave" value="{{ old('prix_epave') }}">
                                                @error('prix_epave')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="vehicle_description" class="form-label">Description du véhicule</label>
                                        <textarea class="form-control" id="vehicle_description" name="vehicle_description" rows="2">{{ old('vehicle_description') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="photo_principale" class="form-label">Photo principale du véhicule</label>
                                        <input type="file" class="form-control" id="photo_principale" name="photo_principale" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Informations de la pièce -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Informations de la pièce</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nom" class="form-label required">Nom de la pièce</label>
                                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                                   id="nom" name="nom" value="{{ old('nom') }}" required
                                                   placeholder="Ex: Alternateur, Pare-chocs avant, etc.">
                                            @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="ville" class="form-label required">Ville</label>
                                            <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                                   id="ville" name="ville"
                                                   value="{{ old('ville') ?? (Auth::user()->casse->ville ?? '') }}"
                                                   placeholder="Ex: Lomé, Sokodé..."
                                                   required>
                                            @error('ville')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="reference_constructeur" class="form-label">Référence constructeur</label>
                                            <input type="text" class="form-control @error('reference_constructeur') is-invalid @enderror"
                                                   id="reference_constructeur" name="reference_constructeur" value="{{ old('reference_constructeur') }}"
                                                   placeholder="Ex: 123456789-ABC">
                                            @error('reference_constructeur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="prix" class="form-label required">Prix (FCFA)</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0"
                                                       class="form-control @error('prix') is-invalid @enderror"
                                                       id="prix" name="prix" value="{{ old('prix') }}" required
                                                       placeholder="0.00">
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                            @error('prix')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantite" class="form-label required">Quantité en stock</label>
                                            <input type="number" min="1" class="form-control @error('quantite') is-invalid @enderror"
                                                   id="quantite" name="quantite" value="{{ old('quantite', 1) }}" required>
                                            @error('quantite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="etat_piece" class="form-label required">État de la pièce</label>
                                            <select class="form-select @error('etat_piece') is-invalid @enderror" id="etat_piece" name="etat_piece" required>
                                                <option value="">Sélectionner un état</option>
                                                <option value="neuf" {{ old('etat_piece') == 'neuf' ? 'selected' : '' }}>Neuf</option>
                                                <option value="tres_bon" {{ old('etat_piece') == 'tres_bon' ? 'selected' : '' }}>Très bon état</option>
                                                <option value="bon" {{ old('etat_piece') == 'bon' ? 'selected' : '' }}>Bon état</option>
                                                <option value="moyen" {{ old('etat_piece') == 'moyen' ? 'selected' : '' }}>État moyen</option>
                                                <option value="usage" {{ old('etat_piece') == 'usage' ? 'selected' : '' }}>État d'usage</option>
                                            </select>
                                            @error('etat_piece')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 form-check form-switch">
                                            <input type="hidden" name="disponible" value="0">
                                            <input class="form-check-input" type="checkbox" id="disponible" name="disponible"
                                                   value="1" {{ old('disponible', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="disponible">Disponible à la vente</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label required">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4" required
                                              placeholder="Décrivez la pièce en détail...">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Décrivez l'état, les spécificités, les défauts éventuels...</div>
                                </div>

                                <div class="mb-3">
                                    <label for="compatible_avec" class="form-label">Véhicules compatibles</label>
                                    <textarea class="form-control @error('compatible_avec') is-invalid @enderror"
                                              id="compatible_avec" name="compatible_avec" rows="2"
                                              placeholder="Ex: Renault Clio III 2008-2012, Peugeot 308 2010-2013...">{{ old('compatible_avec') }}</textarea>
                                    @error('compatible_avec')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Listez les modèles compatibles (séparés par des virgules)</div>
                                </div>

                                <div class="mb-4">
                                    <label for="photos" class="form-label">Photos de la pièce</label>
                                    <input type="file" class="form-control @error('photos.*') is-invalid @enderror"
                                           id="photos" name="photos[]" multiple accept="image/*">
                                    @error('photos.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Formats acceptés: JPG, PNG, GIF. Taille max: 2MB par image.
                                    </div>
                                    <div id="imagePreview" class="mt-3 row g-2" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Ajouter la pièce
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Toggle nouvelle sélection véhicule
    const existingRadio = document.getElementById('existing_vehicle');
    const newRadio = document.getElementById('new_vehicle');
    const existingSection = document.getElementById('existing_vehicle_section');
    const newSection = document.getElementById('new_vehicle_section');

    existingRadio.addEventListener('change', toggleVehicleSection);
    newRadio.addEventListener('change', toggleVehicleSection);

    function toggleVehicleSection() {
        if(newRadio.checked) {
            newSection.style.display = 'block';
            existingSection.style.display = 'none';
        } else {
            newSection.style.display = 'none';
            existingSection.style.display = 'block';
        }
    }

    // Preview images
    document.getElementById('photos').addEventListener('change', function() {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        if(this.files.length > 0) preview.style.display = 'flex';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.classList.add('col-3');
                div.innerHTML = `<img src="${e.target.result}" class="img-thumbnail">`;
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
