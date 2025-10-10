@extends('layouts.app')

@section('title', 'Modifier la pièce')

@section('content')
    <div class="container">

        <h1 class="mb-4">Modifier la pièce</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('pieces.update', $piece) }}" method="POST" enctype="multipart/form-data" id="pieceForm">
            @csrf
            @method('PUT')

            {{-- Nom de la pièce --}}
            <div class="mb-3">
                <label for="nom_piece_id" class="form-label">Nom de la pièce *</label>
                <div class="input-group">
                    <select name="nom_piece_id" id="nom_piece_id" class="form-select @error('nom_piece_id') is-invalid @enderror">
                        <option value="">Sélectionner un nom de pièce</option>
                        @foreach($nomPieces as $nomPiece)
                            <option value="{{ $nomPiece->id }}" {{ (old('nom_piece_id', $piece->nom_piece_id) == $nomPiece->id) ? 'selected' : '' }}>
                                {{ $nomPiece->nom }} @if($nomPiece->categorie) ({{ $nomPiece->categorie }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-secondary" id="toggleNewNomPiece">
                        <i class="bi bi-plus-lg"></i> Nouveau
                    </button>
                </div>
                @error('nom_piece_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('new_nom_piece')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nouveau nom de pièce (caché par défaut) --}}
            <div class="mb-3" id="newNomPieceField" style="display: none;">
                <label for="new_nom_piece" class="form-label">Nouveau nom de pièce</label>
                <div class="input-group">
                    <input type="text" name="new_nom_piece" id="new_nom_piece" class="form-control"
                           value="{{ old('new_nom_piece') }}" placeholder="Entrez le nouveau nom de pièce">
                    <button type="button" class="btn btn-outline-danger" id="cancelNewNomPiece">
                        <i class="bi bi-x-lg"></i> Annuler
                    </button>
                </div>
                <small class="text-muted">Ce nouveau nom sera ajouté à la liste</small>
            </div>

            {{-- Marque --}}
            <div class="mb-3">
                <label for="marque_id" class="form-label">Marque du véhicule *</label>
                <div class="input-group">
                    <select name="marque_id" id="marque_id" class="form-select @error('marque_id') is-invalid @enderror">
                        <option value="">Sélectionner une marque</option>
                        @foreach($marques as $marque)
                            <option value="{{ $marque->id }}" {{ (old('marque_id', $piece->marque_id) == $marque->id) ? 'selected' : '' }}>
                                {{ $marque->nom }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-secondary" id="toggleNewMarque">
                        <i class="bi bi-plus-lg"></i> Nouvelle
                    </button>
                </div>
                @error('marque_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('new_marque')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nouvelle marque (cachée par défaut) --}}
            <div class="mb-3" id="newMarqueField" style="display: none;">
                <label for="new_marque" class="form-label">Nouvelle marque</label>
                <div class="input-group">
                    <input type="text" name="new_marque" id="new_marque" class="form-control"
                           value="{{ old('new_marque') }}" placeholder="Entrez le nom de la nouvelle marque">
                    <button type="button" class="btn btn-outline-danger" id="cancelNewMarque">
                        <i class="bi bi-x-lg"></i> Annuler
                    </button>
                </div>
                <small class="text-muted">Cette nouvelle marque sera ajoutée à la liste</small>
            </div>

            {{-- Modèle --}}
            <div class="mb-3">
                <label for="modele_id" class="form-label">Modèle du véhicule *</label>
                <div class="input-group">
                    <select name="modele_id" id="modele_id" class="form-select @error('modele_id') is-invalid @enderror">
                        <option value="">Sélectionner un modèle</option>
                        @foreach($modeles as $modele)
                            <option value="{{ $modele->id }}" {{ (old('modele_id', $piece->modele_id) == $modele->id) ? 'selected' : '' }}>
                                {{ $modele->nom }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-secondary" id="toggleNewModele">
                        <i class="bi bi-plus-lg"></i> Nouveau
                    </button>
                </div>
                @error('modele_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('new_modele')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nouveau modèle (caché par défaut) --}}
            <div class="mb-3" id="newModeleField" style="display: none;">
                <label for="new_modele" class="form-label">Nouveau modèle</label>
                <div class="input-group">
                    <input type="text" name="new_modele" id="new_modele" class="form-control"
                           value="{{ old('new_modele') }}" placeholder="Entrez le nom du nouveau modèle">
                    <button type="button" class="btn btn-outline-danger" id="cancelNewModele">
                        <i class="bi bi-x-lg"></i> Annuler
                    </button>
                </div>
                <small class="text-muted">Ce nouveau modèle sera ajouté à la liste</small>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                          rows="4">{{ old('description', $piece->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Prix --}}
            <div class="mb-3">
                <label for="prix" class="form-label">Prix (≥ 500 FCFA) *</label>
                <input type="number" name="prix" id="prix" class="form-control @error('prix') is-invalid @enderror"
                       value="{{ old('prix', $piece->prix) }}" min="500" step="0.01">
                @error('prix')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Quantité --}}
            <div class="mb-3">
                <label for="quantite" class="form-label">Quantité disponible (≥ 1) *</label>
                <input type="number" name="quantite" id="quantite" class="form-control @error('quantite') is-invalid @enderror"
                       value="{{ old('quantite', $piece->quantite) }}" min="1">
                @error('quantite')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- État --}}
            <div class="mb-3">
                <label for="etat" class="form-label">État *</label>
                <select name="etat" id="etat" class="form-select @error('etat') is-invalid @enderror" required>
                    <option value="">Sélectionner un état</option>
                    @foreach(['neuf','tres_bon','bon','moyen','usage'] as $etatOption)
                        <option value="{{ $etatOption }}" {{ old('etat', $piece->etat) == $etatOption ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $etatOption)) }}
                        </option>
                    @endforeach
                </select>
                @error('etat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Référence constructeur (lecture seule) --}}
            <div class="mb-3">
                <label for="reference_display" class="form-label">Référence constructeur</label>
                <input type="text" id="reference_display" class="form-control"
                       value="{{ $piece->reference_constructeur }}" readonly>
                <small class="text-muted">La référence sera mise à jour automatiquement si vous changez la marque, le modèle ou le nom de la pièce</small>
            </div>

            {{-- Compatible avec --}}
            <div class="mb-3">
                <label for="compatible_avec" class="form-label">Compatible avec *</label>
                <textarea name="compatible_avec" id="compatible_avec" class="form-control @error('compatible_avec') is-invalid @enderror"
                          rows="2" placeholder="Ex: Toyota Corolla 2015-2020, Honda Civic 2016-2021">{{ old('compatible_avec', $piece->compatible_avec) }}</textarea>
                @error('compatible_avec')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Listez les véhicules compatibles avec cette pièce</small>
            </div>

            {{-- Photos actuelles --}}
            @if($piece->photos && count($piece->photos) > 0)
                <div class="mb-3">
                    <label class="form-label">Photos actuelles</label>
                    <div class="row">
                        @foreach($piece->photos as $photo)
                            <div class="col-md-3 mb-2">
                                <img src="{{ Storage::url($photo) }}" class="img-thumbnail" alt="Photo de la pièce">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Nouvelles photos --}}
            <div class="mb-3">
                <label for="photos" class="form-label">Nouvelles photos (remplacera les anciennes si chargées)</label>
                <input type="file" name="photos[]" id="photos" class="form-control @error('photos.*') is-invalid @enderror"
                       multiple accept="image/*">
                @error('photos.*')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Formats acceptés: JPG, PNG, GIF. Taille max: 2 Mo par image</small>
            </div>


            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Mettre à jour
                </button>
                <a href="{{ route('pieces.show', $piece) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables pour les champs nom de pièce
            const nomPieceSelect = document.getElementById('nom_piece_id');
            const toggleNewNomPiece = document.getElementById('toggleNewNomPiece');
            const newNomPieceField = document.getElementById('newNomPieceField');
            const cancelNewNomPiece = document.getElementById('cancelNewNomPiece');
            const newNomPieceInput = document.getElementById('new_nom_piece');

            // Variables pour les champs marque
            const marqueSelect = document.getElementById('marque_id');
            const toggleNewMarque = document.getElementById('toggleNewMarque');
            const newMarqueField = document.getElementById('newMarqueField');
            const cancelNewMarque = document.getElementById('cancelNewMarque');
            const newMarqueInput = document.getElementById('new_marque');

            // Variables pour les champs modèle
            const modeleSelect = document.getElementById('modele_id');
            const toggleNewModele = document.getElementById('toggleNewModele');
            const newModeleField = document.getElementById('newModeleField');
            const cancelNewModele = document.getElementById('cancelNewModele');
            const newModeleInput = document.getElementById('new_modele');

            // Gestion nouveau nom de pièce
            toggleNewNomPiece.addEventListener('click', function() {
                nomPieceSelect.disabled = true;
                nomPieceSelect.value = '';
                newNomPieceField.style.display = 'block';
                newNomPieceInput.focus();
            });

            cancelNewNomPiece.addEventListener('click', function() {
                nomPieceSelect.disabled = false;
                newNomPieceField.style.display = 'none';
                newNomPieceInput.value = '';
            });

            // Gestion nouvelle marque
            toggleNewMarque.addEventListener('click', function() {
                marqueSelect.disabled = true;
                marqueSelect.value = '';
                newMarqueField.style.display = 'block';
                newMarqueInput.focus();

                // Désactiver et réinitialiser le modèle
                modeleSelect.disabled = true;
                modeleSelect.value = '';
                modeleSelect.innerHTML = '<option value="">Sélectionner d\'abord une marque</option>';
                toggleNewModele.disabled = true;
                newModeleField.style.display = 'none';
                newModeleInput.value = '';
            });

            cancelNewMarque.addEventListener('click', function() {
                marqueSelect.disabled = false;
                newMarqueField.style.display = 'none';
                newMarqueInput.value = '';
            });

            // Gestion nouveau modèle
            toggleNewModele.addEventListener('click', function() {
                modeleSelect.disabled = true;
                modeleSelect.value = '';
                newModeleField.style.display = 'block';
                newModeleInput.focus();
            });

            cancelNewModele.addEventListener('click', function() {
                modeleSelect.disabled = false;
                newModeleField.style.display = 'none';
                newModeleInput.value = '';
            });

            // Charger les modèles quand une marque est sélectionnée
            marqueSelect.addEventListener('change', function() {
                const marqueId = this.value;

                if (!marqueId) {
                    modeleSelect.disabled = true;
                    modeleSelect.innerHTML = '<option value="">Sélectionner d\'abord une marque</option>';
                    toggleNewModele.disabled = true;
                    newModeleField.style.display = 'none';
                    newModeleInput.value = '';
                    return;
                }

                // Activer le bouton nouveau modèle
                toggleNewModele.disabled = false;

                // Charger les modèles via AJAX
                fetch(`/api/marques/${marqueId}/modeles`)
                    .then(response => response.json())
                    .then(data => {
                        const currentModeleId = '{{ old("modele_id", $piece->modele_id) }}';
                        modeleSelect.innerHTML = '<option value="">Sélectionner un modèle</option>';

                        data.forEach(modele => {
                            const option = document.createElement('option');
                            option.value = modele.id;
                            option.textContent = modele.nom;
                            if (modele.id == currentModeleId) {
                                option.selected = true;
                            }
                            modeleSelect.appendChild(option);
                        });

                        modeleSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des modèles:', error);
                        modeleSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            });

            // Si une marque est déjà sélectionnée au chargement, charger ses modèles
            if (marqueSelect.value) {
                // Marquer que nous sommes en édition pour conserver le modèle sélectionné
                marqueSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
