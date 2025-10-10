@extends('layouts.app')

@section('title', 'Créer une demande de vente')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Vendre mon véhicule / épave</h1>
            <a href="{{ route('demandes-epaves.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('demandes-epaves.store') }}" method="POST" enctype="multipart/form-data" id="demandeForm">
                    @csrf

                    <!-- Type de vente -->
                    <div class="mb-4">
                        <h5 class="mb-3">Type de vente</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-check-inline p-3 border rounded" style="width: 100%;">
                                    <input class="form-check-input" type="radio" name="type" id="type_vehicule"
                                           value="vehicule" {{ old('type', 'vehicule') === 'vehicule' ? 'checked' : '' }} required>
                                    <label class="form-check-label ms-2" for="type_vehicule">
                                        <i class="fas fa-car text-primary fa-2x d-block mb-2"></i>
                                        <strong>Véhicule en bon état</strong>
                                        <p class="text-muted small mb-0">Véhicule fonctionnel et en état de rouler</p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline p-3 border rounded" style="width: 100%;">
                                    <input class="form-check-input" type="radio" name="type" id="type_epave"
                                           value="epave" {{ old('type') === 'epave' ? 'checked' : '' }} required>
                                    <label class="form-check-label ms-2" for="type_epave">
                                        <i class="fas fa-car-crash text-danger fa-2x d-block mb-2"></i>
                                        <strong>Épave / Véhicule accidenté</strong>
                                        <p class="text-muted small mb-0">Véhicule hors d'usage ou accidenté</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Informations du véhicule</h5>

                            <!-- Marque -->
                            <div class="mb-3">
                                <label for="marque" class="form-label">Marque *</label>
                                <select class="form-select" id="marque" name="marque" required>
                                    <option value="">-- Sélectionner une marque --</option>
                                    @foreach($marques as $marque)
                                        <option value="{{ $marque->nom }}" data-id="{{ $marque->id }}"
                                            {{ old('marque') === $marque->nom ? 'selected' : '' }}>
                                            {{ $marque->nom }}
                                        </option>
                                    @endforeach
                                    <option value="autre" {{ old('marque') === 'autre' ? 'selected' : '' }}>
                                        ➕ Ajouter une nouvelle marque
                                    </option>
                                </select>
                            </div>

                            <!-- Champ pour nouvelle marque -->
                            <div class="mb-3" id="nouvelle_marque_div" style="display: none;">
                                <label for="marque_autre" class="form-label">Nouvelle marque *</label>
                                <input type="text" class="form-control" id="marque_autre" name="marque_autre"
                                       value="{{ old('marque_autre') }}" placeholder="Ex: Toyota, Peugeot...">
                            </div>

                            <!-- Modèle -->
                            <div class="mb-3">
                                <label for="modele" class="form-label">Modèle *</label>
                                <select class="form-select" id="modele" name="modele" required disabled>
                                    <option value="">-- Sélectionner d'abord une marque --</option>
                                </select>
                            </div>

                            <!-- Champ pour nouveau modèle -->
                            <div class="mb-3" id="nouveau_modele_div" style="display: none;">
                                <label for="modele_autre" class="form-label">Nouveau modèle *</label>
                                <input type="text" class="form-control" id="modele_autre" name="modele_autre"
                                       value="{{ old('modele_autre') }}" placeholder="Ex: Corolla, 308...">
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
                                            <option value="essence" {{ old('carburant') === 'essence' ? 'selected' : '' }}>Essence</option>
                                            <option value="diesel" {{ old('carburant') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                            <option value="hybride" {{ old('carburant') === 'hybride' ? 'selected' : '' }}>Hybride</option>
                                            <option value="electrique" {{ old('carburant') === 'electrique' ? 'selected' : '' }}>Électrique</option>
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
                                    <option value="bon" {{ old('etat') === 'bon' ? 'selected' : '' }}>Bon état</option>
                                    <option value="moyen" {{ old('etat') === 'moyen' ? 'selected' : '' }}>État moyen</option>
                                    <option value="mauvais" {{ old('etat') === 'mauvais' ? 'selected' : '' }}>Mauvais état</option>
                                    <option value="epave" {{ old('etat') === 'epave' ? 'selected' : '' }}>Épave (accidenté)</option>
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
                                <input type="text" class="form-control" id="numero_chassis" name="numero_chassis"
                                       required value="{{ old('numero_chassis') }}">
                            </div>

                            <div class="mb-3">
                                <label for="numero_plaque" class="form-label">Numéro de plaque *</label>
                                <input type="text" class="form-control" id="numero_plaque" name="numero_plaque"
                                       required value="{{ old('numero_plaque') }}">
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
                                <input type="text" class="form-control" id="telephone_contact" name="telephone_contact"
                                       required value="{{ old('telephone_contact') }}">
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
                            Votre demande sera visible par les casses professionnelles et acheteurs de votre région.
                            Ils pourront vous faire des offres pour l'achat de votre véhicule.
                        </p>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Publier la demande
                    </button>
                </form>
            </div>
        </div>
    </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const marqueSelect = document.getElementById('marque');
                const modeleSelect = document.getElementById('modele');
                const nouvelleMarqueDiv = document.getElementById('nouvelle_marque_div');
                const nouveauModeleDiv = document.getElementById('nouveau_modele_div');
                const marqueAutreInput = document.getElementById('marque_autre');
                const modeleAutreInput = document.getElementById('modele_autre');

                // Gestion de la sélection de marque
                marqueSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];

                    if (this.value === 'autre') {
                        // Afficher le champ pour nouvelle marque
                        nouvelleMarqueDiv.style.display = 'block';
                        marqueAutreInput.required = true;

                        // Réinitialiser le modèle
                        modeleSelect.innerHTML = '<option value="autre">➕ Ajouter un nouveau modèle</option>';
                        modeleSelect.disabled = false;
                        nouveauModeleDiv.style.display = 'none';
                        modeleAutreInput.required = false;
                    } else if (this.value) {
                        // Masquer le champ nouvelle marque
                        nouvelleMarqueDiv.style.display = 'none';
                        marqueAutreInput.required = false;

                        const marqueId = selectedOption.getAttribute('data-id');

                        if (marqueId) {
                            // Charger les modèles via AJAX
                            fetch(`/api/marques/${marqueId}/modeles-epave`)
                                .then(response => response.json())
                                .then(data => {
                                    modeleSelect.innerHTML = '<option value="">-- Sélectionner un modèle --</option>';

                                    data.forEach(modele => {
                                        const option = document.createElement('option');
                                        option.value = modele.nom;
                                        option.textContent = modele.nom;
                                        modeleSelect.appendChild(option);
                                    });

                                    // Ajouter l'option "Autre"
                                    const optionAutre = document.createElement('option');
                                    optionAutre.value = 'autre';
                                    optionAutre.textContent = '➕ Ajouter un nouveau modèle';
                                    modeleSelect.appendChild(optionAutre);

                                    modeleSelect.disabled = false;
                                })
                                .catch(error => {
                                    console.error('Erreur:', error);
                                    modeleSelect.innerHTML = '<option value="autre">➕ Ajouter un nouveau modèle</option>';
                                    modeleSelect.disabled = false;
                                });
                        }
                    } else {
                        // Aucune marque sélectionnée
                        nouvelleMarqueDiv.style.display = 'none';
                        marqueAutreInput.required = false;
                        modeleSelect.innerHTML = '<option value="">-- Sélectionner d\'abord une marque --</option>';
                        modeleSelect.disabled = true;
                        nouveauModeleDiv.style.display = 'none';
                        modeleAutreInput.required = false;
                    }
                });

                // Gestion de la sélection de modèle
                modeleSelect.addEventListener('change', function() {
                    if (this.value === 'autre') {
                        nouveauModeleDiv.style.display = 'block';
                        modeleAutreInput.required = true;
                    } else {
                        nouveauModeleDiv.style.display = 'none';
                        modeleAutreInput.required = false;
                    }
                });

                // Vérification avant soumission
                document.getElementById('demandeForm').addEventListener('submit', function(e) {
                    if (marqueSelect.value === 'autre' && !marqueAutreInput.value.trim()) {
                        e.preventDefault();
                        alert('Veuillez saisir le nom de la nouvelle marque');
                        marqueAutreInput.focus();
                        return false;
                    }

                    if (modeleSelect.value === 'autre' && !modeleAutreInput.value.trim()) {
                        e.preventDefault();
                        alert('Veuillez saisir le nom du nouveau modèle');
                        modeleAutreInput.focus();
                        return false;
                    }
                });
            });
        </script>
@endsection
