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
                        <h5 class="mb-3">Type de vente *</h5>
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
                                        <label for="annee" class="form-label">Année * <small class="text-muted">(1900 - {{ date('Y') }})</small></label>
                                        <input type="number" class="form-control" id="annee" name="annee" required
                                               min="1900" max="{{ date('Y') }}" value="{{ old('annee') }}"
                                               placeholder="Ex: 2020"
                                               oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4); if(this.value > {{ date('Y') }}) this.value = {{ date('Y') }};">
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
                                            <option value="">-- Sélectionner --</option>
                                            <option value="essence" {{ old('carburant') === 'essence' ? 'selected' : '' }}>Essence</option>
                                            <option value="diesel" {{ old('carburant') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                            <option value="hybride" {{ old('carburant') === 'hybride' ? 'selected' : '' }}>Hybride</option>
                                            <option value="electrique" {{ old('carburant') === 'electrique' ? 'selected' : '' }}>Électrique</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kilometrage" class="form-label">Kilométrage * <small class="text-muted">(1 - 500 000 km)</small></label>
                                        <input type="number" class="form-control" id="kilometrage" name="kilometrage" required
                                               min="1" max="500000" value="{{ old('kilometrage') }}"
                                               oninput="if(this.value > 500000) this.value = 500000; if(this.value < 0) this.value = '';"
                                               placeholder="Ex: 125000">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="etat" class="form-label">État du véhicule *</label>
                                <select class="form-select" id="etat" name="etat" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="bon" {{ old('etat') === 'bon' ? 'selected' : '' }}>Bon état</option>
                                    <option value="moyen" {{ old('etat') === 'moyen' ? 'selected' : '' }}>État moyen</option>
                                    <option value="mauvais" {{ old('etat') === 'mauvais' ? 'selected' : '' }}>Mauvais état</option>
                                    <option value="epave" {{ old('etat') === 'epave' ? 'selected' : '' }}>Épave (accidenté)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="prix_souhaite" class="form-label">Prix souhaité (FCFA)</label>
                                <input type="number" step="0.01" class="form-control" id="prix_souhaite"
                                       name="prix_souhaite" value="{{ old('prix_souhaite') }}" min="0">
                                <div class="form-text">Laissez vide pour recevoir des offres</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Informations supplémentaires</h5>

                            <div class="mb-3">
                                <label for="numero_chassis" class="form-label">Numéro de châssis * <small class="text-muted">(16 caractères)</small></label>
                                <input type="text"
                                       class="form-control"
                                       id="numero_chassis"
                                       name="numero_chassis"
                                       required
                                       value="{{ old('numero_chassis') }}"
                                       maxlength="16"
                                       pattern="[A-Z0-9]{16}"
                                       placeholder="Ex: 1HGBH41JXMN109186"
                                       oninput="this.value = this.value.replace(/[^A-Z0-9]/g, '').toUpperCase().slice(0, 16)"
                                       style="text-transform: uppercase;">
                                <div class="form-text">
                                    16 caractères (lettres et chiffres) - <span id="chassis-count">0</span>/16
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="numero_plaque" class="form-label">Numéro de plaque * <small class="text-muted">(2 lettres + 4 chiffres)</small></label>
                                <input type="text"
                                       class="form-control"
                                       id="numero_plaque"
                                       name="numero_plaque"
                                       required
                                       value="{{ old('numero_plaque') }}"
                                       maxlength="6"
                                       pattern="[A-Z]{2}[0-9]{4}"
                                       placeholder="Ex: AB1234"
                                       oninput="formatPlaque(this)"
                                       style="text-transform: uppercase;">
                                <div class="form-text">
                                    Format: 2 lettres majuscules + 4 chiffres (Ex: AB1234) - <span id="plaque-count">0</span>/6
                                </div>
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

                    <h5 class="mb-3" hidden>Informations de contact</h5>

                    <div class="row" hidden>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone_contact" class="form-label">Téléphone de contact *</label>
                                <input type="text" class="form-control" id="telephone_contact" name="telephone_contact"
                                       required value="{{ old('telephone_contact', $casse->telephone) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse où se trouve le véhicule *</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required value="{{ old('adresse', $casse->adresse) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required value="{{ old('email', $casse->email) }}">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Information importante</h6>
                        <ul class="mb-0">
                            <li>Tous les champs marqués d'un astérisque (*) sont obligatoires</li>
                            <li>Votre publication sera visible par les utilisateurs</li>
                            <li>Les utilisateurs pourront vous faire des propositions de prix</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Publier la demande
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Compteur pour le numéro de châssis
        document.getElementById('numero_chassis').addEventListener('input', function() {
            document.getElementById('chassis-count').textContent = this.value.length;
        });

        // Formater le numéro de plaque (2 lettres + 4 chiffres)
        function formatPlaque(input) {
            let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');

            // Séparer les lettres et les chiffres
            let letters = '';
            let numbers = '';

            for (let char of value) {
                if (/[A-Z]/.test(char) && letters.length < 2 && numbers.length === 0) {
                    letters += char;
                } else if (/[0-9]/.test(char) && letters.length === 2) {
                    if (numbers.length < 4) {
                        numbers += char;
                    }
                }
            }

            // Construire la valeur finale
            input.value = letters + numbers;

            // Mettre à jour le compteur
            document.getElementById('plaque-count').textContent = input.value.length;

            // Validation personnalisée
            if (letters.length === 2 && numbers.length === 4) {
                input.setCustomValidity('');
            } else {
                input.setCustomValidity('Le numéro de plaque doit contenir exactement 2 lettres suivies de 4 chiffres');
            }
        }

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
                // Vérification marque
                if (marqueSelect.value === 'autre' && !marqueAutreInput.value.trim()) {
                    e.preventDefault();
                    alert('Veuillez saisir le nom de la nouvelle marque');
                    marqueAutreInput.focus();
                    return false;
                }

                // Vérification modèle
                if (modeleSelect.value === 'autre' && !modeleAutreInput.value.trim()) {
                    e.preventDefault();
                    alert('Veuillez saisir le nom du nouveau modèle');
                    modeleAutreInput.focus();
                    return false;
                }

                // Vérification châssis
                const chassis = document.getElementById('numero_chassis').value;
                if (chassis.length !== 16) {
                    e.preventDefault();
                    alert('Le numéro de châssis doit contenir exactement 16 caractères');
                    document.getElementById('numero_chassis').focus();
                    return false;
                }

                // Vérification plaque
                const plaque = document.getElementById('numero_plaque').value;
                const plaqueRegex = /^[A-Z]{2}[0-9]{4}$/;
                if (!plaqueRegex.test(plaque)) {
                    e.preventDefault();
                    alert('Le numéro de plaque doit contenir exactement 2 lettres suivies de 4 chiffres (Ex: AB1234)');
                    document.getElementById('numero_plaque').focus();
                    return false;
                }

                // Vérification kilométrage
                const km = document.getElementById('kilometrage').value;
                if (km < 1 || km > 500000) {
                    e.preventDefault();
                    alert('Le kilométrage doit être entre 1 et 500 000 km');
                    document.getElementById('kilometrage').focus();
                    return false;
                }
            });
        });
    </script>
@endsection
