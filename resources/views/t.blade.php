

    <?php
// resources/views/demandes-epaves/create.blade.php
    ?>
    @extends('layouts.app')

    @section('title', 'Vendre mon épave')

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('demandes-epaves.index') }}">Demandes d'épaves</a></li>
        <li class="breadcrumb-item active">Créer</li>
    @endsection

    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-4"><i class="fas fa-plus me-2"></i>Vendre mon épave</h1>
                    <p class="lead text-muted mb-4">Créez une demande de vente et recevez des offres de nos casses automobiles partenaires</p>
                </div>
            </div>

            <form action="{{ route('demandes-epaves.store') }}" method="POST" enctype="multipart/form-data" id="demande-form">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Informations du véhicule -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-car me-2"></i>Informations du véhicule</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="marque" class="form-label">Marque *</label>
                                        <input type="text" class="form-control @error('marque') is-invalid @enderror"
                                               id="marque" name="marque" value="{{ old('marque') }}" required
                                               list="marques-list">
                                        <datalist id="marques-list">
                                            <option value="Peugeot">
                                            <option value="Renault">
                                            <option value="Toyota">
                                            <option value="Nissan">
                                            <option value="Hyundai">
                                            <option value="Kia">
                                            <option value="Ford">
                                            <option value="Volkswagen">
                                            <option value="Citroën">
                                            <option value="Opel">
                                            <option value="BMW">
                                            <option value="Mazda">
                                            <option value="Yaris">
                                            <option value="Mercedes-Benz">
                                            <option value="Audi">
                                        </datalist>
                                        @error('marque')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="modele" class="form-label">Modèle *</label>
                                        <input type="text" class="form-control @error('modele') is-invalid @enderror"
                                               id="modele" name="modele" value="{{ old('modele') }}" required>
                                        @error('modele')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="annee" class="form-label">Année *</label>
                                        <input type="number" class="form-control @error('annee') is-invalid @enderror"
                                               id="annee" name="annee" value="{{ old('annee') }}"
                                               min="1980" max="{{ date('Y') }}" required>
                                        @error('annee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="couleur" class="form-label">Couleur *</label>
                                        <input type="text" class="form-control @error('couleur') is-invalid @enderror"
                                               id="couleur" name="couleur" value="{{ old('couleur') }}" required
                                               list="couleurs-list">
                                        <datalist id="couleurs-list">
                                            <option value="Blanc">
                                            <option value="Noir">
                                            <option value="Gris">
                                            <option value="Rouge">
                                            <option value="Bleu">
                                            <option value="Vert">
                                            <option value="Jaune">
                                            <option value="Argent">
                                            <option value="Beige">
                                            <option value="Marron">
                                        </datalist>
                                        @error('couleur')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="carburant" class="form-label">Carburant *</label>
                                        <select class="form-select @error('carburant') is-invalid @enderror"
                                                id="carburant" name="carburant" required>
                                            <option value="">Sélectionner...</option>
                                            <option value="essence" {{ old('carburant') === 'essence' ? 'selected' : '' }}>Essence</option>
                                            <option value="diesel" {{ old('carburant') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                            <option value="hybride" {{ old('carburant') === 'hybride' ? 'selected' : '' }}>Hybride</option>
                                            <option value="electrique" {{ old('carburant') === 'electrique' ? 'selected' : '' }}>Électrique</option>
                                        </select>
                                        @error('carburant')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="kilometrage" class="form-label">Kilométrage *</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('kilometrage') is-invalid @enderror"
                                                   id="kilometrage" name="kilometrage" value="{{ old('kilometrage') }}"
                                                   min="0" required>
                                            <span class="input-group-text">km</span>
                                        </div>
                                        @error('kilometrage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="etat" class="form-label">État général *</label>
                                        <select class="form-select @error('etat') is-invalid @enderror"
                                                id="etat" name="etat" required>
                                            <option value="">Sélectionner...</option>
                                            <option value="bon" {{ old('etat') === 'bon' ? 'selected' : '' }}>Bon (quelques réparations)</option>
                                            <option value="moyen" {{ old('etat') === 'moyen' ? 'selected' : '' }}>Moyen (réparations importantes)</option>
                                            <option value="mauvais" {{ old('etat') === 'mauvais' ? 'selected' : '' }}>Mauvais (accidenté)</option>
                                            <option value="epave" {{ old('etat') === 'epave' ? 'selected' : '' }}>Épave (pour pièces)</option>
                                        </select>
                                        @error('etat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Identification -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Identification</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="numero_chassis" class="form-label">Numéro de châssis *</label>
                                        <input type="text" class="form-control @error('numero_chassis') is-invalid @enderror"
                                               id="numero_chassis" name="numero_chassis" value="{{ old('numero_chassis') }}"
                                               required maxlength="17">
                                        <small class="form-text text-muted">17 caractères (VIN)</small>
                                        @error('numero_chassis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="numero_plaque" class="form-label">Numéro de plaque *</label>
                                        <input type="text" class="form-control @error('numero_plaque') is-invalid @enderror"
                                               id="numero_plaque" name="numero_plaque" value="{{ old('numero_plaque') }}" required>
                                        @error('numero_plaque')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description et prix -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>Description et prix</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description détaillée *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="5" required
                                              placeholder="Décrivez l'état du véhicule, les dommages, ce qui fonctionne encore, les raisons de la vente...">{{ old('description') }}</textarea>
                                    <small class="form-text text-muted">Plus votre description est détaillée, plus vous recevrez d'offres pertinentes</small>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="prix_souhaite" class="form-label">Prix souhaité</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('prix_souhaite') is-invalid @enderror"
                                                   id="prix_souhaite" name="prix_souhaite" value="{{ old('prix_souhaite') }}"
                                                   min="0" step="50">
                                            <span class="input-group-text">FCFA</span>
                                        </div>
                                        <small class="form-text text-muted">Optionnel - Laissez vide pour "À négocier"</small>
                                        @error('prix_souhaite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Informations de contact</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="telephone_contact" class="form-label">Téléphone *</label>
                                        <input type="tel" class="form-control @error('telephone_contact') is-invalid @enderror"
                                               id="telephone_contact" name="telephone_contact"
                                               value="{{ old('telephone_contact', auth()->user()->telephone) }}" required>
                                        @error('telephone_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse où se trouve le véhicule *</label>
                                    <textarea class="form-control @error('adresse') is-invalid @enderror"
                                              id="adresse" name="adresse" rows="3" required
                                              placeholder="Adresse complète pour la récupération du véhicule">{{ old('adresse', auth()->user()->adresse) }}</textarea>
                                    @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Photos -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Photos du véhicule</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="photos" class="form-label">Photos *</label>
                                    <input type="file" class="form-control @error('photos.*') is-invalid @enderror"
                                           id="photos" name="photos[]" accept="image/*" multiple required>
                                    <small class="form-text text-muted">
                                        Ajoutez au moins 3 photos : vue d'ensemble, dommages, intérieur<br>
                                        Format: JPG, PNG. Taille max: 2MB chacune
                                    </small>
                                    @error('photos.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="photos_preview" class="mt-3"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Conseils -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Conseils</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>Prenez des photos sous différents angles</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>Photographiez les dommages en détail</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>Mentionnez les pièces qui fonctionnent</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>Soyez honnête sur l'état réel</small>
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <small>Répondez rapidement aux offres</small>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Estimation -->
                        <div class="card mb-4" id="estimation-card" style="display: none;">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Estimation</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <p class="text-muted mb-2">Estimation basée sur :</p>
                                    <h4 class="text-primary" id="estimation-value">-</h4>
                                    <small class="text-muted">Cette estimation est indicative</small>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        <span class="btn-text">Publier ma demande</span>
                                        <span class="loading spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                                    </button>
                                    <a href="{{ route('demandes-epaves.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                                    </a>
                                </div>

                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Votre demande sera visible par toutes nos casses partenaires
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @push('scripts')
            <script>
                // Prévisualisation des images
                document.getElementById('photos').addEventListener('change', function() {
                    const files = this.files;
                    const preview = document.getElementById('photos_preview');
                    preview.innerHTML = '';

                    if (files.length < 3) {
                        showToast('Ajoutez au moins 3 photos pour une meilleure visibilité', 'warning');
                    }

                    if (files.length > 8) {
                        showToast('Maximum 8 photos autorisées', 'warning');
                        this.value = '';
                        return;
                    }

                    Array.from(files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'position-relative d-inline-block me-2 mb-2';
                            div.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                        onclick="removePreviewImage(this)" style="transform: translate(50%, -50%);">
                    <i class="fas fa-times"></i>
                </button>
            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });

                function removePreviewImage(button) {
                    button.closest('.position-relative').remove();
                }

                // Estimation automatique du prix
                function calculateEstimation() {
                    const marque = document.getElementById('marque').value;
                    const annee = parseInt(document.getElementById('annee').value);
                    const kilometrage = parseInt(document.getElementById('kilometrage').value);
                    const etat = document.getElementById('etat').value;

                    if (marque && annee && kilometrage && etat) {
                        // Calcul simplifié d'estimation
                        let basePrice = 2000; // Prix de base

                        // Ajustement par marque
                        const marqueMultiplier = {
                            'BMW': 1.5,
                            'Mercedes-Benz': 1.5,
                            'Audi': 1.4,
                            'Toyota': 1.2,
                            'Peugeot': 1.0,
                            'Renault': 1.0,
                            'Citroën': 0.9
                        };

                        basePrice *= marqueMultiplier[marque] || 1.0;

                        // Ajustement par âge
                        const age = new Date().getFullYear() - annee;
                        basePrice *= Math.max(0.3, 1 - (age * 0.05));

                        // Ajustement par kilométrage
                        if (kilometrage > 200000) basePrice *= 0.7;
                        else if (kilometrage > 150000) basePrice *= 0.8;
                        else if (kilometrage > 100000) basePrice *= 0.9;

                        // Ajustement par état
                        const etatMultiplier = {
                            'bon': 1.0,
                            'moyen': 0.7,
                            'mauvais': 0.4,
                            'epave': 0.2
                        };

                        basePrice *= etatMultiplier[etat] || 0.5;

                        // Affichage de l'estimation
                        const estimation = Math.round(basePrice / 50) * 50; // Arrondir aux 50FCFA
                        document.getElementById('estimation-value').textContent = estimation + 'FCFA';
                        document.getElementById('estimation-card').style.display = 'block';

                        // Suggestion de prix
                        const prixSouhaiteInput = document.getElementById('prix_souhaite');
                        if (!prixSouhaiteInput.value) {
                            prixSouhaiteInput.value = estimation;
                        }
                    }
                }

                // Calculer l'estimation quand les champs changent
                ['marque', 'annee', 'kilometrage', 'etat'].forEach(fieldId => {
                    document.getElementById(fieldId).addEventListener('change', calculateEstimation);
                });

                // Auto-complétion des modèles
                document.getElementById('marque').addEventListener('change', function() {
                    const marque = this.value;
                    const modeleInput = document.getElementById('modele');

                    const modeles = {
                        'Peugeot': ['206', '207', '208', '307', '308', '407', '508', '607'],
                        'Renault': ['Clio', 'Megane', 'Laguna', 'Scenic', 'Kangoo', 'Twingo'],
                        'Toyota': ['Corolla', 'Camry', 'Yaris', 'Auris', 'Avensis', 'RAV4'],
                        'Nissan': ['Micra', 'Primera', 'Almera', 'X-Trail', 'Qashqai']
                    };

                    if (modeles[marque]) {
                        modeleInput.setAttribute('list', 'modeles-list');

                        let datalist = document.getElementById('modeles-list');
                        if (!datalist) {
                            datalist = document.createElement('datalist');
                            datalist.id = 'modeles-list';
                            document.body.appendChild(datalist);
                        }

                        datalist.innerHTML = '';
                        modeles[marque].forEach(modele => {
                            const option = document.createElement('option');
                            option.value = modele;
                            datalist.appendChild(option);
                        });
                    }
                });

                // Formatage automatique
                document.getElementById('numero_chassis').addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });

                // Validation du formulaire
                document.getElementById('demande-form').addEventListener('submit', function(e) {
                    const photos = document.getElementById('photos').files;
                    if (photos.length < 3) {
                        e.preventDefault();
                        showToast('Veuillez ajouter au moins 3 photos', 'error');
                        return;
                    }

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const btnText = submitBtn.querySelector('.btn-text');
                    const loading = submitBtn.querySelector('.loading');

                    btnText.textContent = 'Publication...';
                    loading.style.display = 'inline-block';
                    submitBtn.disabled = true;
                });
            </script>
        @endpush
    @endsection

        <?php
// resources/views/notifications/index.blade.php
        ?>
    @extends('layouts.app')

    @section('title', 'Mes notifications')

    @section('breadcrumb')
        <li class="breadcrumb-item active">Notifications</li>
    @endsection

    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1><i class="fas fa-bell me-2"></i>Mes notifications</h1>
                        @if($notifications->where('lu', false)->count() > 0)
                            <a href="{{ route('notifications.read-all') }}" class="btn btn-outline-primary">
                                <i class="fas fa-check-double me-2"></i>Tout marquer comme lu
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    @forelse($notifications as $notification)
                        <div class="card mb-3 {{ $notification->lu ? '' : 'border-primary' }}">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center
                                            {{ $notification->lu ? 'bg-light text-muted' : $notification->type_badge_class . ' text-white' }}"
                                             style="width: 50px; height: 50px;">
                                            <i class="{{ $notification->type_icon }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 {{ $notification->lu ? 'text-muted' : '' }}">
                                                    {{ $notification->titre }}
                                                    @if(!$notification->lu)
                                                        <span class="badge bg-primary ms-2">Nouveau</span>
                                                    @endif
                                                </h6>
                                                <p class="mb-2 {{ $notification->lu ? 'text-muted' : '' }}">
                                                    {{ $notification->message }}
                                                </p>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" type="button"
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if(!$notification->lu)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('notifications.read', $notification) }}">
                                                                <i class="fas fa-check me-2"></i>Marquer comme lu
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($notification->commande_id)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('commandes.show', $notification->commande_id) }}">
                                                                <i class="fas fa-external-link-alt me-2"></i>Voir la commande
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-bell-slash fa-5x text-muted mb-4"></i>
                                <h3>Aucune notification</h3>
                                <p class="text-muted mb-4">Vous n'avez pas encore reçu de notifications.</p>
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $notifications->links() }}                            @error('reference_constructeur')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="4" required
                              placeholder="Décrivez l'état de la pièce, son fonctionnement, etc.">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        </div>
        </div>

        <!-- Compatibilité -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-puzzle-piece me-2"></i>Compatibilité</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Compatible avec</label>
                    <div id="compatible-container">
                        @if(old('compatible_avec'))
                            @foreach(old('compatible_avec') as $index => $compatible)
                                <div class="input-group mb-2 compatible-item">
                                    <input type="text" name="compatible_avec[]" class="form-control"
                                           value="{{ $compatible }}" placeholder="Ex: Peugeot 206, Citroën C2">
                                    <button type="button" class="btn btn-outline-danger remove-compatible">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="input-group mb-2 compatible-item">
                                <input type="text" name="compatible_avec[]" class="form-control"
                                       placeholder="Ex: Peugeot 206, Citroën C2">
                                <button type="button" class="btn btn-outline-danger remove-compatible">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-compatible">
                        <i class="fas fa-plus me-1"></i>Ajouter une compatibilité
                    </button>
                    <small class="form-text text-muted d-block mt-2">
                        Indiquez les véhicules avec lesquels cette pièce est compatible
                    </small>
                </div>
            </div>
        </div>
        </div>

        <div class="col-lg-4">
            <!-- Photos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Photos de la pièce</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="photos" class="form-label">Photos</label>
                        <input type="file" class="form-control @error('photos.*') is-invalid @enderror"
                               id="photos" name="photos[]" accept="image/*" multiple>
                        <small class="form-text text-muted">Sélectionnez plusieurs photos (max 5). Format: JPG, PNG. Taille max: 2MB chacune</small>
                        @error('photos.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="photos_preview" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <!-- Informations sur le véhicule sélectionné -->
            <div class="card mb-4" id="vehicle-info" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i>Véhicule sélectionné</h5>
                </div>
                <div class="card-body" id="vehicle-details">
                    <!-- Les détails seront chargés dynamiquement -->
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>
                            <span class="btn-text">Enregistrer la pièce</span>
                            <span class="loading spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                        </button>
                        <a href="{{ route('pieces.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                        @if(request('vehicle_id'))
                            <a href="{{ route('vehicles.show', request('vehicle_id')) }}" class="btn btn-outline-info">
                                <i class="fas fa-car me-2"></i>Voir le véhicule
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
        </form>
        </div>

        @push('scripts')
            <script>
                // Prévisualisation des images
                document.getElementById('photos').addEventListener('change', function() {
                    const files = this.files;
                    const preview = document.getElementById('photos_preview');
                    preview.innerHTML = '';

                    if (files.length > 5) {
                        showToast('Maximum 5 photos autorisées', 'warning');
                        this.value = '';
                        return;
                    }

                    Array.from(files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'position-relative d-inline-block me-2 mb-2';
                            div.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                        onclick="removePreviewImage(this)" style="transform: translate(50%, -50%);">
                    <i class="fas fa-times"></i>
                </button>
            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });

                function removePreviewImage(button) {
                    button.closest('.position-relative').remove();
                }

                // Gestion des compatibilités
                document.getElementById('add-compatible').addEventListener('click', function() {
                    const container = document.getElementById('compatible-container');
                    const newItem = document.createElement('div');
                    newItem.className = 'input-group mb-2 compatible-item';
                    newItem.innerHTML = `
        <input type="text" name="compatible_avec[]" class="form-control"
               placeholder="Ex: Peugeot 206, Citroën C2">
        <button type="button" class="btn btn-outline-danger remove-compatible">
            <i class="fas fa-times"></i>
        </button>
    `;
                    container.appendChild(newItem);
                });

                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-compatible') || e.target.closest('.remove-compatible')) {
                        const button = e.target.classList.contains('remove-compatible') ? e.target : e.target.closest('.remove-compatible');
                        const items = document.querySelectorAll('.compatible-item');
                        if (items.length > 1) {
                            button.closest('.compatible-item').remove();
                        } else {
                            showToast('Au moins une compatibilité doit être renseignée', 'warning');
                        }
                    }
                });

                // Chargement des informations du véhicule
                document.getElementById('vehicle_id').addEventListener('change', function() {
                    const vehicleId = this.value;
                    const vehicleInfo = document.getElementById('vehicle-info');
                    const vehicleDetails = document.getElementById('vehicle-details');

                    if (vehicleId) {
                        // Simulation du chargement des données du véhicule
                        vehicleInfo.style.display = 'block';
                        vehicleDetails.innerHTML = `
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
        `;

                        // Ici vous feriez un appel AJAX pour récupérer les détails du véhicule
                        fetch(`/api/vehicles/${vehicleId}`)
                            .then(response => response.json())
                            .then(data => {
                                vehicleDetails.innerHTML = `
                    <div class="row">
                        <div class="col-12 mb-2">
                            <strong>${data.marque} ${data.modele}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Année:</small><br>
                            <span>${data.annee}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Plaque:</small><br>
                            <span>${data.numero_plaque}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted">Carburant:</small><br>
                            <span>${data.carburant}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <small class="text-muted">État:</small><br>
                            <span class="badge bg-secondary">${data.etat}</span>
                        </div>
                    </div>
                `;
                            })
                            .catch(error => {
                                console.error('Erreur:', error);
                                vehicleDetails.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Impossible de charger les détails du véhicule
                    </div>
                `;
                            });
                    } else {
                        vehicleInfo.style.display = 'none';
                    }
                });

                // Auto-suggestion pour le nom de la pièce
                document.getElementById('nom').addEventListener('input', function() {
                    const value = this.value.toLowerCase();

                    // Suggestions de description basées sur le nom de la pièce
                    const suggestions = {
                        'moteur': 'Moteur en bon état de fonctionnement, révision récente. Kilométrage: ',
                        'boîte de vitesses': 'Boîte de vitesses fonctionnelle, passage des vitesses correct. ',
                        'phare': 'Optique en bon état, sans fissure. Fonctionnement électrique vérifié.',
                        'portière': 'Portière complète avec mécanisme de fermeture. État de la peinture: ',
                        'capot': 'Capot en bon état, sans déformation majeure. ',
                        'pare-chocs': 'Pare-chocs sans fissure importante. Quelques rayures d\'usage.',
                        'batterie': 'Batterie testée et fonctionnelle. Capacité: ',
                        'alternateur': 'Alternateur testé, charge correctement. ',
                        'démarreur': 'Démarreur fonctionnel, testé sur véhicule.'
                    };

                    const descriptionField = document.getElementById('description');
                    if (!descriptionField.value) {
                        for (const [key, suggestion] of Object.entries(suggestions)) {
                            if (value.includes(key)) {
                                descriptionField.value = suggestion;
                                break;
                            }
                        }
                    }
                });

                // Validation du formulaire
                document.getElementById('piece-form').addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const btnText = submitBtn.querySelector('.btn-text');
                    const loading = submitBtn.querySelector('.loading');

                    // Vérifier qu'au moins un véhicule est sélectionné
                    const vehicleSelect = document.getElementById('vehicle_id');
                    if (!vehicleSelect.value) {
                        e.preventDefault();
                        showToast('Veuillez sélectionner un véhicule', 'error');
                        return;
                    }

                    btnText.textContent = 'Enregistrement...';
                    loading.style.display = 'inline-block';
                    submitBtn.disabled = true;
                });

                // Initialisation
                document.addEventListener('DOMContentLoaded', function() {
                    // Si un véhicule est pré-sélectionné, charger ses informations
                    const vehicleSelect = document.getElementById('vehicle_id');
                    if (vehicleSelect.value) {
                        vehicleSelect.dispatchEvent(new Event('change'));
                    }
                });
            </script>
        @endpush
    @endsection

        <?php
// resources/views/demandes-epaves/index.blade.php
        ?>
    @extends('layouts.app')

    @section('title', auth()->user()->isCasse() ? 'Demandes d\'épaves' : 'Mes demandes de vente')

    @section('breadcrumb')
        <li class="breadcrumb-item active">
            {{ auth()->user()->isCasse() ? 'Demandes d\'épaves' : 'Mes demandes de vente' }}
        </li>
    @endsection

    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1>
                                <i class="fas fa-handshake me-2"></i>
                                {{ auth()->user()->isCasse() ? 'Demandes d\'épaves disponibles' : 'Mes demandes de vente' }}
                            </h1>
                            <p class="text-muted mb-0">
                                {{ auth()->user()->isCasse() ? 'Trouvez des véhicules à récupérer et faites vos offres' : 'Gérez vos demandes de vente d\'épaves' }}
                            </p>
                        </div>
                        @if(auth()->user()->isClient())
                            <a href="{{ route('demandes-epaves.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Vendre mon épave
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filtres pour les casses -->
            @if(auth()->user()->isCasse())
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('demandes-epaves.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Marque, modèle..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="marque" class="form-select">
                                        <option value="">Toutes marques</option>
                                        <option value="Peugeot" {{ request('marque') === 'Peugeot' ? 'selected' : '' }}>Peugeot</option>
                                        <option value="Renault" {{ request('marque') === 'Renault' ? 'selected' : '' }}>Renault</option>
                                        <option value="Toyota" {{ request('marque') === 'Toyota' ? 'selected' : '' }}>Toyota</option>
                                        <option value="Nissan" {{ request('marque') === 'Nissan' ? 'selected' : '' }}>Nissan</option>
                                        <option value="Hyundai" {{ request('marque') === 'Hyundai' ? 'selected' : '' }}>Hyundai</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="etat" class="form-select">
                                        <option value="">Tous états</option>
                                        <option value="bon" {{ request('etat') === 'bon' ? 'selected' : '' }}>Bon</option>
                                        <option value="moyen" {{ request('etat') === 'moyen' ? 'selected' : '' }}>Moyen</option>
                                        <option value="mauvais" {{ request('etat') === 'mauvais' ? 'selected' : '' }}>Mauvais</option>
                                        <option value="epave" {{ request('etat') === 'epave' ? 'selected' : '' }}>Épave</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="prix_max" class="form-control" placeholder="Prix max"
                                           value="{{ request('prix_max') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="tri" class="form-select">
                                        <option value="recent" {{ request('tri') === 'recent' ? 'selected' : '' }}>Plus récent</option>
                                        <option value="ancien" {{ request('tri') === 'ancien' ? 'selected' : '' }}>Plus ancien</option>
                                        <option value="prix_asc" {{ request('tri') === 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                                        <option value="prix_desc" {{ request('tri') === 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Statistiques pour les clients -->
            @if(auth()->user()->isClient())
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $demandes->where('statut', 'en_attente')->count() }}</h4>
                                        <p class="mb-0">En attente</p>
                                    </div>
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $demandes->where('statut', 'interesse')->count() }}</h4>
                                        <p class="mb-0">Offres reçues</p>
                                    </div>
                                    <i class="fas fa-handshake fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $demandes->where('statut', 'accepte')->count() }}</h4>
                                        <p class="mb-0">Vendues</p>
                                    </div>
                                    <i class="fas fa-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $demandes->sum('offres_count') }}</h4>
                                        <p class="mb-0">Total offres</p>
                                    </div>
                                    <i class="fas fa-euro-sign fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Liste des demandes -->
            <div class="row">
                @forelse($demandes as $demande)
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 card-hover">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $demande->marque }} {{ $demande->modele }} ({{ $demande->annee }})</h6>
                                <span class="badge {{ $demande->statut_badge_class }}">
                            {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                        </span>
                            </div>

                            @if($demande->photos && count($demande->photos) > 0)
                                <div id="carousel-{{ $demande->id }}" class="carousel slide">
                                    <div class="carousel-inner">
                                        @foreach($demande->photos as $index => $photo)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="{{ Storage::url($photo) }}" class="d-block w-100"
                                                     style="height: 200px; object-fit: cover;" alt="Photo {{ $index + 1 }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($demande->photos) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $demande->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $demande->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-car fa-3x text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Kilométrage:</small><br>
                                        <span>{{ number_format($demande->kilometrage, 0, ',', ' ') }} km</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">État:</small><br>
                                        <span class="badge bg-secondary">{{ ucfirst($demande->etat) }}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Carburant:</small><br>
                                        <span>{{ ucfirst($demande->carburant) }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Prix souhaité:</small><br>
                                        <strong class="text-primary">
                                            {{ $demande->prix_souhaite ? number_format($demande->prix_souhaite, 0, ',', ' ') . 'FCFA' : 'À négocier' }}
                                        </strong>
                                    </div>
                                </div>

                                <p class="text-muted small mb-3">{{ Str::limit($demande->description, 100) }}</p>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $demande->created_at->diffForHumans() }}
                                    </small>
                                    @if($demande->offres_count > 0)
                                        <small class="text-info">
                                            <i class="fas fa-handshake me-1"></i>{{ $demande->offres_count }} offre(s)
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('demandes-epaves.show', $demande) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Voir détails
                                    </a>

                                    @if(auth()->user()->isCasse() && $demande->statut === 'en_attente')
                                        @if(!$demande->hasOffreFrom(auth()->id()))
                                            <button type="button" class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#offreModal-{{ $demande->id }}">
                                                <i class="fas fa-handshake me-1"></i>Faire une offre
                                            </button>
                                        @else
                                            <span class="badge bg-info">Offre envoyée</span>
                                        @endif
                                    @elseif(auth()->user()->isClient() && auth()->user()->id === $demande->user_id)
                                        <div class="btn-group">
                                            @if($demande->canBeModified())
                                                <a href="{{ route('demandes-epaves.edit', $demande) }}" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            @if($demande->canBeDeleted())
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="deleteDemande({{ $demande->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal pour faire une offre (casses uniquement) -->
                    @if(auth()->user()->isCasse() && $demande->statut === 'en_attente' && !$demande->hasOffreFrom(auth()->id()))
                        <div class="modal fade" id="offreModal-{{ $demande->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('demandes-epaves.faire-offre', $demande) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Faire une offre</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <h6>{{ $demande->marque }} {{ $demande->modele }} ({{ $demande->annee }})</h6>
                                                <p class="text-muted small">{{ $demande->description }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label for="prix_offert_{{ $demande->id }}" class="form-label">Prix offert *</label>
                                                <div class="input-group">
                                                    <input type="number" name="prix_offert" id="prix_offert_{{ $demande->id }}"
                                                           class="form-control" min="0" step="50" required
                                                           placeholder="{{ $demande->prix_souhaite ?? 1000 }}">
                                                    <span class="input-group-text">FCFA</span>
                                                </div>
                                                @if($demande->prix_souhaite)
                                                    <small class="text-muted">Prix souhaité: {{ number_format($demande->prix_souhaite, 0, ',', ' ') }}FCFA</small>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label for="message_{{ $demande->id }}" class="form-label">Message (optionnel)</label>
                                                <textarea name="message" id="message_{{ $demande->id }}" class="form-control" rows="3"
                                                          placeholder="Précisions sur votre offre, conditions, délais..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-handshake me-2"></i>Envoyer l'offre
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-search fa-5x text-muted mb-4"></i>
                                @if(auth()->user()->isCasse())
                                    <h3>Aucune demande d'épave disponible</h3>
                                    <p class="text-muted mb-4">Il n'y a actuellement aucune demande d'épave correspondant à vos critères.</p>
                                    <a href="{{ route('demandes-epaves.index') }}" class="btn btn-primary">
                                        <i class="fas fa-refresh me-2"></i>Actualiser
                                    </a>
                                @else
                                    <h3>Aucune demande de vente</h3>
                                    <p class="text-mute.timeline-item.completed .timeline-marker {
    background: #28a745;
    box-shadow: 0 0 0 3px #28a745;
}

.timeline-content h6 {
    margin-bottom: 5px;
}

@media print {
    .btn, .modal, .navbar, .breadcrumb {
        display: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function cancelOrder() {
    confirmDelete(() => {
        fetch('{{ route("commandes.annuler", $commande) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                    }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                    if (data.success) {
                                    window.location.reload();
                                    } else {
                                    showToast('Erreur lors de l\'annulation', 'error');
                                    }
                                    })
                                    .catch(error => {
                                    console.error('Erreur:', error);
                                    showToast('Erreur lors de l\'annulation', 'error');
                                    });
                                    });
                                    }
                                    </script>
                                    @endpush
                                    @endsection

                                        <?php
// resources/views/vehicles/create.blade.php
                                        ?>
                                    @extends('layouts.app')

                                    @section('title', 'Ajouter un véhicule')

                                    @section('breadcrumb')
                                        <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}">Véhicules</a></li>
                                        <li class="breadcrumb-item active">Ajouter</li>
                                    @endsection

                                    @section('content')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h1 class="mb-4"><i class="fas fa-plus me-2"></i>Ajouter un véhicule</h1>
                                                </div>
                                            </div>

                                            <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data" id="vehicle-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <!-- Informations générales -->
                                                        <div class="card mb-4">
                                                            <div class="card-header">
                                                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations générales</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="marque" class="form-label">Marque *</label>
                                                                        <input type="text" class="form-control @error('marque') is-invalid @enderror"
                                                                               id="marque" name="marque" value="{{ old('marque') }}" required
                                                                               list="marques-list">
                                                                        <datalist id="marques-list">
                                                                            <option value="Peugeot">
                                                                            <option value="Renault">
                                                                            <option value="Toyota">
                                                                            <option value="Nissan">
                                                                            <option value="Hyundai">
                                                                            <option value="Kia">
                                                                            <option value="Ford">
                                                                            <option value="Volkswagen">
                                                                            <option value="Citroën">
                                                                            <option value="Opel">
                                                                            <option value="BMW">
                                                                            <option value="Mercedes-Benz">
                                                                            <option value="Audi">
                                                                            <option value="Honda">
                                                                            <option value="Mazda">
                                                                        </datalist>
                                                                        @error('marque')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="modele" class="form-label">Modèle *</label>
                                                                        <input type="text" class="form-control @error('modele') is-invalid @enderror"
                                                                               id="modele" name="modele" value="{{ old('modele') }}" required>
                                                                        @error('modele')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="annee" class="form-label">Année *</label>
                                                                        <input type="number" class="form-control @error('annee') is-invalid @enderror"
                                                                               id="annee" name="annee" value="{{ old('annee') }}"
                                                                               min="1900" max="{{ date('Y') + 1 }}" required>
                                                                        @error('annee')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="couleur" class="form-label">Couleur *</label>
                                                                        <input type="text" class="form-control @error('couleur') is-invalid @enderror"
                                                                               id="couleur" name="couleur" value="{{ old('couleur') }}" required
                                                                               list="couleurs-list">
                                                                        <datalist id="couleurs-list">
                                                                            <option value="Blanc">
                                                                            <option value="Noir">
                                                                            <option value="Gris">
                                                                            <option value="Rouge">
                                                                            <option value="Bleu">
                                                                            <option value="Vert">
                                                                            <option value="Jaune">
                                                                            <option value="Orange">
                                                                            <option value="Violet">
                                                                            <option value="Marron">
                                                                            <option value="Beige">
                                                                            <option value="Argent">
                                                                        </datalist>
                                                                        @error('couleur')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="carburant" class="form-label">Carburant *</label>
                                                                        <select class="form-select @error('carburant') is-invalid @enderror"
                                                                                id="carburant" name="carburant" required>
                                                                            <option value="">Sélectionner...</option>
                                                                            <option value="essence" {{ old('carburant') === 'essence' ? 'selected' : '' }}>Essence</option>
                                                                            <option value="diesel" {{ old('carburant') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                                                            <option value="hybride" {{ old('carburant') === 'hybride' ? 'selected' : '' }}>Hybride</option>
                                                                            <option value="electrique" {{ old('carburant') === 'electrique' ? 'selected' : '' }}>Électrique</option>
                                                                        </select>
                                                                        @error('carburant')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="transmission" class="form-label">Transmission *</label>
                                                                        <select class="form-select @error('transmission') is-invalid @enderror"
                                                                                id="transmission" name="transmission" required>
                                                                            <option value="">Sélectionner...</option>
                                                                            <option value="manuelle" {{ old('transmission') === 'manuelle' ? 'selected' : '' }}>Manuelle</option>
                                                                            <option value="automatique" {{ old('transmission') === 'automatique' ? 'selected' : '' }}>Automatique</option>
                                                                        </select>
                                                                        @error('transmission')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="kilometrage" class="form-label">Kilométrage *</label>
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control @error('kilometrage') is-invalid @enderror"
                                                                                   id="kilometrage" name="kilometrage" value="{{ old('kilometrage') }}"
                                                                                   min="0" required>
                                                                            <span class="input-group-text">km</span>
                                                                        </div>
                                                                        @error('kilometrage')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Identification -->
                                                        <div class="card mb-4">
                                                            <div class="card-header">
                                                                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Identification</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="numero_chassis" class="form-label">Numéro de châssis *</label>
                                                                        <input type="text" class="form-control @error('numero_chassis') is-invalid @enderror"
                                                                               id="numero_chassis" name="numero_chassis" value="{{ old('numero_chassis') }}"
                                                                               required maxlength="17">
                                                                        <small class="form-text text-muted">17 caractères maximum</small>
                                                                        @error('numero_chassis')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="numero_plaque" class="form-label">Numéro de plaque *</label>
                                                                        <input type="text" class="form-control @error('numero_plaque') is-invalid @enderror"
                                                                               id="numero_plaque" name="numero_plaque" value="{{ old('numero_plaque') }}" required>
                                                                        @error('numero_plaque')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- État et prix -->
                                                        <div class="card mb-4">
                                                            <div class="card-header">
                                                                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>État et prix</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="etat" class="form-label">État *</label>
                                                                        <select class="form-select @error('etat') is-invalid @enderror"
                                                                                id="etat" name="etat" required>
                                                                            <option value="">Sélectionner...</option>
                                                                            <option value="bon" {{ old('etat') === 'bon' ? 'selected' : '' }}>Bon</option>
                                                                            <option value="moyen" {{ old('etat') === 'moyen' ? 'selected' : '' }}>Moyen</option>
                                                                            <option value="mauvais" {{ old('etat') === 'mauvais' ? 'selected' : '' }}>Mauvais</option>
                                                                            <option value="epave" {{ old('etat') === 'epave' ? 'selected' : '' }}>Épave</option>
                                                                        </select>
                                                                        @error('etat')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="date_arrivee" class="form-label">Date d'arrivée *</label>
                                                                        <input type="date" class="form-control @error('date_arrivee') is-invalid @enderror"
                                                                               id="date_arrivee" name="date_arrivee" value="{{ old('date_arrivee', date('Y-m-d')) }}" required>
                                                                        @error('date_arrivee')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="prix_epave" class="form-label">Prix de l'épave *</label>
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control @error('prix_epave') is-invalid @enderror"
                                                                                   id="prix_epave" name="prix_epave" value="{{ old('prix_epave') }}"
                                                                                   min="0" step="0.01" required>
                                                                            <span class="input-group-text">FCFA</span>
                                                                        </div>
                                                                        @error('prix_epave')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Description -->
                                                        <div class="card mb-4">
                                                            <div class="card-header">
                                                                <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>Description</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <label for="description" class="form-label">Description détaillée</label>
                                                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                                                              id="description" name="description" rows="4"
                                                                              placeholder="Décrivez l'état du véhicule, les dommages, les pièces fonctionnelles...">{{ old('description') }}</textarea>
                                                                    @error('description')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <!-- Photos -->
                                                        <div class="card mb-4">
                                                            <div class="card-header">
                                                                <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Photos</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <label for="photo_principale" class="form-label">Photo principale</label>
                                                                    <input type="file" class="form-control @error('photo_principale') is-invalid @enderror"
                                                                           id="photo_principale" name="photo_principale" accept="image/*">
                                                                    <small class="form-text text-muted">Format: JPG, PNG. Taille max: 2MB</small>
                                                                    @error('photo_principale')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                    <div id="photo_principale_preview" class="mt-2"></div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="photos_additionnelles" class="form-label">Photos additionnelles</label>
                                                                    <input type="file" class="form-control @error('photos_additionnelles.*') is-invalid @enderror"
                                                                           id="photos_additionnelles" name="photos_additionnelles[]"
                                                                           accept="image/*" multiple>
                                                                    <small class="form-text text-muted">Sélectionnez plusieurs photos (max 5)</small>
                                                                    @error('photos_additionnelles.*')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                    <div id="photos_additionnelles_preview" class="mt-2"></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Data scan -->
                                                        <div class="card mb-4">
                                                            <div class="card-header">
                                                                <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Data Scan</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="text-center">
                                                                    <button type="button" class="btn btn-outline-primary" id="scan-btn">
                                                                        <i class="fas fa-camera me-2"></i>Scanner le véhicule
                                                                    </button>
                                                                    <p class="small text-muted mt-2">Utilisez votre caméra pour scanner les informations du véhicule</p>
                                                                </div>
                                                                <div id="scan-result" class="mt-3 d-none">
                                                                    <div class="alert alert-success">
                                                                        <i class="fas fa-check me-2"></i>Données scannées avec succès
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Actions -->
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="d-grid gap-2">
                                                                    <button type="submit" class="btn btn-success btn-lg">
                                                                        <i class="fas fa-save me-2"></i>
                                                                        <span class="btn-text">Enregistrer le véhicule</span>
                                                                        <span class="loading spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                                                                    </button>
                                                                    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">
                                                                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        @push('scripts')
                                            <script>
                                                // Prévisualisation des images
                                                function previewImage(input, previewContainer) {
                                                    const files = input.files;
                                                    previewContainer.innerHTML = '';

                                                    if (files) {
                                                        Array.from(files).forEach((file, index) => {
                                                            if (index < 5) { // Limiter à 5 images
                                                                const reader = new FileReader();
                                                                reader.onload = function(e) {
                                                                    const img = document.createElement('img');
                                                                    img.src = e.target.result;
                                                                    img.className = 'img-thumbnail me-2 mb-2';
                                                                    img.style.width = '80px';
                                                                    img.style.height = '80px';
                                                                    img.style.objectFit = 'cover';
                                                                    previewContainer.appendChild(img);
                                                                };
                                                                reader.readAsDataURL(file);
                                                            }
                                                        });
                                                    }
                                                }

                                                // Gestionnaire pour la photo principale
                                                document.getElementById('photo_principale').addEventListener('change', function() {
                                                    const preview = document.getElementById('photo_principale_preview');
                                                    previewImage(this, preview);
                                                });

                                                // Gestionnaire pour les photos additionnelles
                                                document.getElementById('photos_additionnelles').addEventListener('change', function() {
                                                    const preview = document.getElementById('photos_additionnelles_preview');
                                                    previewImage(this, preview);
                                                });

                                                // Auto-complétion des modèles basée sur la marque
                                                document.getElementById('marque').addEventListener('change', function() {
                                                    const marque = this.value;
                                                    const modeleInput = document.getElementById('modele');

                                                    // Ici vous pourriez faire un appel AJAX pour récupérer les modèles
                                                    // Pour la démo, on utilise des données statiques
                                                    const modeles = {
                                                        'Peugeot': ['206', '207', '208', '307', '308', '407', '508'],
                                                        'Renault': ['Clio', 'Megane', 'Laguna', 'Scenic', 'Kangoo'],
                                                        'Toyota': ['Corolla', 'Camry', 'Yaris', 'Auris', 'Avensis'],
                                                        'Nissan': ['Micra', 'Primera', 'Almera', 'X-Trail', 'Qashqai']
                                                    };

                                                    if (modeles[marque]) {
                                                        modeleInput.setAttribute('list', 'modeles-list-' + marque);

                                                        // Créer la datalist des modèles
                                                        let existingDatalist = document.getElementById('modeles-list-' + marque);
                                                        if (existingDatalist) {
                                                            existingDatalist.remove();
                                                        }

                                                        const datalist = document.createElement('datalist');
                                                        datalist.id = 'modeles-list-' + marque;

                                                        modeles[marque].forEach(modele => {
                                                            const option = document.createElement('option');
                                                            option.value = modele;
                                                            datalist.appendChild(option);
                                                        });

                                                        document.body.appendChild(datalist);
                                                    }
                                                });

                                                // Scanner de données (simulation)
                                                document.getElementById('scan-btn').addEventListener('click', function() {
                                                    // Simulation d'un scan
                                                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Scan en cours...';
                                                    this.disabled = true;

                                                    setTimeout(() => {
                                                        // Simuler des données scannées
                                                        document.getElementById('numero_chassis').value = 'VF7XXXXXXXXXX1234';
                                                        document.getElementById('numero_plaque').value = '1234 TG 01';

                                                        document.getElementById('scan-result').classList.remove('d-none');

                                                        this.innerHTML = '<i class="fas fa-check me-2"></i>Scan terminé';
                                                        this.classList.remove('btn-outline-primary');
                                                        this.classList.add('btn-success');
                                                    }, 2000);
                                                });

                                                // Validation du formulaire
                                                document.getElementById('vehicle-form').addEventListener('submit', function(e) {
                                                    const submitBtn = this.querySelector('button[type="submit"]');
                                                    const btnText = submitBtn.querySelector('.btn-text');
                                                    const loading = submitBtn.querySelector('.loading');

                                                    btnText.textContent = 'Enregistrement...';
                                                    loading.style.display = 'inline-block';
                                                    submitBtn.disabled = true;
                                                });

                                                // Formatage automatique du numéro de châssis
                                                document.getElementById('numero_chassis').addEventListener('input', function() {
                                                    this.value = this.value.toUpperCase();
                                                });

                                                // Formatage automatique du prix
                                                document.getElementById('prix_epave').addEventListener('input', function() {
                                                    const value = parseFloat(this.value);
                                                    if (!isNaN(value)) {
                                                        this.value = value.toFixed(2);
                                                    }
                                                });
                                            </script>
                                        @endpush
                                    @endsection

                                        <?php
// resources/views/pieces/create.blade.php
                                        ?>
                                    @extends('layouts.app')

                                    @section('title', 'Ajouter une pièce')

                                    @section('breadcrumb')
                                        <li class="breadcrumb-item"><a href="{{ route('pieces.index') }}">Pièces</a></li>
                                        <li class="breadcrumb-item active">Ajouter</li>
                                    @endsection

                                    @section('content')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h1 class="mb-4"><i class="fas fa-plus me-2"></i>Ajouter une pièce détachée</h1>
                                                </div>
                                            </div>

                                            <form action="{{ route('pieces.store') }}" method="POST" enctype="multipart/form-data" id="piece-form">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <!-- Informations générales -->
                                                        <div class="card mb-4">
                                                            <div class="card-header">
                                                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations générales</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12 mb-3">
                                                                        <label for="vehicle_id" class="form-label">Véhicule d'origine *</label>
                                                                        <select class="form-select @error('vehicle_id') is-invalid @enderror"
                                                                                id="vehicle_id" name="vehicle_id" required>
                                                                            <option value="">Sélectionner un véhicule...</option>
                                                                            @foreach($vehicles as $vehicle)
                                                                                <option value="{{ $vehicle->id }}"
                                                                                    {{ (old('vehicle_id') == $vehicle->id || (isset($selectedVehicle) && $selectedVehicle->id == $vehicle->id)) ? 'selected' : '' }}>
                                                                                    {{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->annee }}) - {{ $vehicle->numero_plaque }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('vehicle_id')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                        @if($vehicles->isEmpty())
                                                                            <div class="alert alert-warning mt-2">
                                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                                Vous devez d'abord <a href="{{ route('vehicles.create') }}">ajouter un véhicule</a> avant de pouvoir ajouter des pièces.
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-8 mb-3">
                                                                        <label for="nom" class="form-label">Nom de la pièce *</label>
                                                                        <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                                                               id="nom" name="nom" value="{{ old('nom') }}" required
                                                                               list="pieces-list" placeholder="Ex: Phare avant droit, Moteur, Portière...">
                                                                        <datalist id="pieces-list">
                                                                            <option value="Moteur">
                                                                            <option value="Boîte de vitesses">
                                                                            <option value="Phare avant droit">
                                                                            <option value="Phare avant gauche">
                                                                            <option value="Feu arrière droit">
                                                                            <option value="Feu arrière gauche">
                                                                            <option value="Portière avant droite">
                                                                            <option value="Portière avant gauche">
                                                                            <option value="Portière arrière droite">
                                                                            <option value="Portière arrière gauche">
                                                                            <option value="Capot">
                                                                            <option value="Coffre">
                                                                            <option value="Pare-chocs avant">
                                                                            <option value="Pare-chocs arrière">
                                                                            <option value="Rétroviseur droit">
                                                                            <option value="Rétroviseur gauche">
                                                                            <option value="Jante alliage">
                                                                            <option value="Pneu">
                                                                            <option value="Batterie">
                                                                            <option value="Alternateur">
                                                                            <option value="Démarreur">
                                                                            <option value="Radiateur">
                                                                            <option value="Compresseur climatisation">
                                                                            <option value="Volant">
                                                                            <option value="Siège avant">
                                                                            <option value="Siège arrière">
                                                                            <option value="Tableau de bord">
                                                                        </datalist>
                                                                        @error('nom')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <label for="etat" class="form-label">État *</label>
                                                                        <select class="form-select @error('etat') is-invalid @enderror"
                                                                                id="etat" name="etat" required>
                                                                            <option value="">Sélectionner...</option>
                                                                            <option value="neuf" {{ old('etat') === 'neuf' ? 'selected' : '' }}>Neuf</option>
                                                                            <option value="tres_bon" {{ old('etat') === 'tres_bon' ? 'selected' : '' }}>Très bon</option>
                                                                            <option value="bon" {{ old('etat') === 'bon' ? 'selected' : '' }}>Bon</option>
                                                                            <option value="moyen" {{ old('etat') === 'moyen' ? 'selected' : '' }}>Moyen</option>
                                                                            <option value="usage" {{ old('etat') === 'usage' ? 'selected' : '' }}>Usagé</option>
                                                                        </select>
                                                                        @error('etat')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="prix" class="form-label">Prix *</label>
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control @error('prix') is-invalid @enderror"
                                                                                   id="prix" name="prix" value="{{ old('prix') }}"
                                                                                   min="0" step="0.01" required>
                                                                            <span class="input-group-text">FCFA</span>
                                                                        </div>
                                                                        @error('prix')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label for="quantite" class="form-label">Quantité en stock *</label>
                                                                        <input type="number" class="form-control @error('quantite') is-invalid @enderror"
                                                                               id="quantite" name="quantite" value="{{ old('quantite', 1) }}"
                                                                               min="1" required>
                                                                        @error('quantite')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="reference_constructeur" class="form-label">Référence constructeur</label>
                                                                    <input type="text" class="form-control @error('reference_constructeur') is-invalid @enderror"
                                                                           id="reference_constructeur" name="reference_constructeur"
                                                                           value="{{ old('reference_constructeur') }}"
                                                                           placeholder="Ex: 6206.L4, PSA-12345">
                                                                    @error('reference_constructeur')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                    @                        <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="nom_complet" class="form-label">Nom complet *</label>
                                                                            <input type="text" class="form-control @error('nom_complet') is-invalid @enderror"
                                                                                   id="nom_complet" name="nom_complet" value="{{ old('nom_complet', auth()->user()->name) }}" required>
                                                                            @error('nom_complet')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="telephone_livraison" class="form-label">Téléphone *</label>
                                                                            <input type="tel" class="form-control @error('telephone_livraison') is-invalid @enderror"
                                                                                   id="telephone_livraison" name="telephone_livraison"
                                                                                   value="{{ old('telephone_livraison', auth()->user()->telephone) }}" required>
                                                                            @error('telephone_livraison')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="adresse_livraison" class="form-label">Adresse de livraison *</label>
                                                                        <textarea class="form-control @error('adresse_livraison') is-invalid @enderror"
                                                                                  id="adresse_livraison" name="adresse_livraison" rows="3" required>{{ old('adresse_livraison', auth()->user()->adresse) }}</textarea>
                                                                        @error('adresse_livraison')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="ville" class="form-label">Ville *</label>
                                                                            <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                                                                   id="ville" name="ville" value="{{ old('ville', auth()->user()->ville) }}" required>
                                                                            @error('ville')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="code_postal" class="form-label">Code postal</label>
                                                                            <input type="text" class="form-control @error('code_postal') is-invalid @enderror"
                                                                                   id="code_postal" name="code_postal" value="{{ old('code_postal', auth()->user()->code_postal) }}">
                                                                            @error('code_postal')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="notes" class="form-label">Instructions spéciales</label>
                                                                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                                                                  id="notes" name="notes" rows="2"
                                                                                  placeholder="Précisions pour la livraison...">{{ old('notes') }}</textarea>
                                                                        @error('notes')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Mode de paiement -->
                                                            <div class="card mb-4">
                                                                <div class="card-header">
                                                                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Mode de paiement</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-check border rounded p-3">
                                                                                <input class="form-check-input" type="radio" name="mode_paiement" id="carte_bancaire" value="carte_bancaire" checked>
                                                                                <label class="form-check-label w-100" for="carte_bancaire">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="fas fa-credit-card fa-2x text-primary me-3"></i>
                                                                                        <div>
                                                                                            <strong>Carte bancaire</strong><br>
                                                                                            <small class="text-muted">Visa, Mastercard</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-check border rounded p-3">
                                                                                <input class="form-check-input" type="radio" name="mode_paiement" id="paypal" value="paypal">
                                                                                <label class="form-check-label w-100" for="paypal">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="fab fa-paypal fa-2x text-info me-3"></i>
                                                                                        <div>
                                                                                            <strong>PayPal</strong><br>
                                                                                            <small class="text-muted">Paiement sécurisé</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-check border rounded p-3">
                                                                                <input class="form-check-input" type="radio" name="mode_paiement" id="virement" value="virement">
                                                                                <label class="form-check-label w-100" for="virement">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="fas fa-university fa-2x text-success me-3"></i>
                                                                                        <div>
                                                                                            <strong>Virement bancaire</strong><br>
                                                                                            <small class="text-muted">Délai 2-3 jours</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <div class="form-check border rounded p-3">
                                                                                <input class="form-check-input" type="radio" name="mode_paiement" id="especes" value="especes">
                                                                                <label class="form-check-label w-100" for="especes">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="fas fa-money-bill-wave fa-2x text-warning me-3"></i>
                                                                                        <div>
                                                                                            <strong>Espèces</strong><br>
                                                                                            <small class="text-muted">À la livraison</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Récapitulatif des articles -->
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Articles commandés</h5>
                                                                </div>
                                                                <div class="card-body p-0">
                                                                    @foreach($panier->items as $item)
                                                                        <div class="border-bottom p-3">
                                                                            <div class="row align-items-center">
                                                                                <div class="col-md-2">
                                                                                    @if($item->piece->photos && count($item->piece->photos) > 0)
                                                                                        <img src="{{ Storage::url($item->piece->photos[0]) }}"
                                                                                             class="img-fluid rounded" alt="{{ $item->piece->nom }}"
                                                                                             style="height: 60px; object-fit: cover;">
                                                                                    @else
                                                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                                                                            <i class="fas fa-image text-muted"></i>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <h6 class="mb-1">{{ $item->piece->nom }}</h6>
                                                                                    <small class="text-muted">
                                                                                        {{ $item->piece->vehicle->marque }} {{ $item->piece->vehicle->modele }}<br>
                                                                                        <i class="fas fa-store me-1"></i>{{ $item->piece->vehicle->casse->nom_entreprise ?? $item->piece->vehicle->casse->name }}
                                                                                    </small>
                                                                                </div>
                                                                                <div class="col-md-2 text-center">
                                                                                    <span class="badge bg-light text-dark">x{{ $item->quantite }}</span>
                                                                                </div>
                                                                                <div class="col-md-2 text-end">
                                                                                    <strong>{{ number_format($item->getSousTotal(), 2, ',', ' ') }}FCFA</strong>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <!-- Récapitulatif de commande -->
                                                            <div class="card sticky-top">
                                                                <div class="card-header">
                                                                    <h5 class="mb-0">Récapitulatif de commande</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between mb-2">
                                                                        <span>Sous-total ({{ $panier->items->count() }} articles)</span>
                                                                        <span>{{ number_format($panier->getTotal(), 2, ',', ' ') }}FCFA</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-2">
                                                                        <span>Frais de livraison</span>
                                                                        <span class="text-success">Gratuits</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-2">
                                                                        <span>TVA (incluse)</span>
                                                                        <span>{{ number_format($panier->getTotal() * 0.18, 2, ',', ' ') }}FCFA</span>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <strong>Total à payer</strong>
                                                                        <strong class="text-primary fs-5">{{ number_format($panier->getTotal(), 2, ',', ' ') }}FCFA</strong>
                                                                    </div>

                                                                    <div class="alert alert-info">
                                                                        <i class="fas fa-info-circle me-2"></i>
                                                                        <small>En passant cette commande, vous acceptez nos conditions générales de vente.</small>
                                                                    </div>

                                                                    <div class="d-grid gap-2">
                                                                        <button type="submit" class="btn btn-success btn-lg" id="submit-order">
                                                                            <i class="fas fa-lock me-2"></i>
                                                                            <span class="btn-text">Confirmer la commande</span>
                                                                            <span class="loading spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                                                                        </button>
                                                                        <a href="{{ route('panier.index') }}" class="btn btn-outline-secondary">
                                                                            <i class="fas fa-arrow-left me-2"></i>Retour au panier
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Informations sur la sécurité -->
                                                            <div class="card mt-4">
                                                                <div class="card-body text-center">
                                                                    <h6><i class="fas fa-shield-alt text-success me-2"></i>Paiement sécurisé</h6>
                                                                    <p class="small text-muted mb-0">Vos informations sont protégées par un cryptage SSL 256 bits</p>
                                                                    <div class="mt-2">
                                                                        <i class="fab fa-cc-visa fa-2x text-muted me-2"></i>
                                                                        <i class="fab fa-cc-mastercard fa-2x text-muted me-2"></i>
                                                                        <i class="fab fa-cc-paypal fa-2x text-muted"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </form>
                                        </div>

                                        @push('scripts')
                                            <script>
                                                document.getElementById('checkout-form').addEventListener('submit', function(e) {
                                                    const submitBtn = document.getElementById('submit-order');
                                                    const btnText = submitBtn.querySelector('.btn-text');
                                                    const loading = submitBtn.querySelector('.loading');

                                                    // Afficher le loader
                                                    btnText.textContent = 'Traitement en cours...';
                                                    loading.style.display = 'inline-block';
                                                    submitBtn.disabled = true;

                                                    // Valider le formulaire
                                                    const requiredFields = this.querySelectorAll('[required]');
                                                    let isValid = true;

                                                    requiredFields.forEach(field => {
                                                        if (!field.value.trim()) {
                                                            isValid = false;
                                                            field.classList.add('is-invalid');
                                                        } else {
                                                            field.classList.remove('is-invalid');
                                                        }
                                                    });

                                                    if (!isValid) {
                                                        e.preventDefault();
                                                        btnText.textContent = 'Confirmer la commande';
                                                        loading.style.display = 'none';
                                                        submitBtn.disabled = false;
                                                        showToast('Veuillez remplir tous les champs obligatoires', 'error');
                                                        return;
                                                    }

                                                    // Le formulaire est valide, continuer la soumission
                                                });

                                                // Auto-remplir les champs avec les données du profil
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    // Copier l'adresse si elle est vide
                                                    const adresseInput = document.getElementById('adresse_livraison');
                                                    if (!adresseInput.value.trim()) {
                                                        adresseInput.value = '{{ auth()->user()->adresse ?? "" }}';
                                                    }
                                                });
                                            </script>
                                        @endpush
                                    @endsection

                                        <?php
// resources/views/commandes/show.blade.php
                                        ?>
                                    @extends('layouts.app')

                                    @section('title', 'Commande ' . $commande->numero_commande)

                                    @section('breadcrumb')
                                        <li class="breadcrumb-item"><a href="{{ route('commandes.index') }}">Commandes</a></li>
                                        <li class="breadcrumb-item active">{{ $commande->numero_commande }}</li>
                                    @endsection

                                    @section('content')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <div>
                                                            <h1>Commande {{ $commande->numero_commande }}</h1>
                                                            <p class="text-muted mb-0">Passée le {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                                                        </div>
                                                        <div class="text-end">
                    <span class="badge {{ $commande->getStatutBadgeClass() }} fs-6 me-2">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                    </span>
                                                            <span class="badge {{ $commande->getStatutPaiementBadgeClass() }} fs-6">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut_paiement)) }}
                    </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <!-- Informations de la commande -->
                                                    <div class="card mb-4">
                                                        <div class="card-header d-flex justify-content-between align-items-center">
                                                            <h5 class="mb-0">Détails de la commande</h5>
                                                            @auth
                                                                @if(auth()->user()->isCasse() && auth()->user()->vehicles()->whereHas('pieces.commandeItems', function($query) use($commande) {
                            $query->where('commande_id', $commande->id);
                        })->exists())
                                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                                                        <i class="fas fa-edit me-1"></i>Mettre à jour le statut
                                                                    </button>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <h6>Informations client</h6>
                                                                    <p class="mb-1"><strong>{{ $commande->user->name }}</strong></p>
                                                                    <p class="mb-1">{{ $commande->user->email }}</p>
                                                                    <p class="mb-0">{{ $commande->telephone_livraison }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6>Adresse de livraison</h6>
                                                                    <p class="mb-0">{{ $commande->adresse_livraison }}</p>
                                                                </div>
                                                            </div>

                                                            @if($commande->notes)
                                                                <div class="mb-3">
                                                                    <h6>Instructions spéciales</h6>
                                                                    <p class="mb-0">{{ $commande->notes }}</p>
                                                                </div>
                                                            @endif

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6>Mode de paiement</h6>
                                                                    <p class="mb-0">
                                                                        @switch($commande->mode_paiement)
                                                                            @case('carte_bancaire')
                                                                                <i class="fas fa-credit-card me-1"></i>Carte bancaire
                                                                                @break
                                                                            @case('paypal')
                                                                                <i class="fab fa-paypal me-1"></i>PayPal
                                                                                @break
                                                                            @case('virement')
                                                                                <i class="fas fa-university me-1"></i>Virement bancaire
                                                                                @break
                                                                            @case('especes')
                                                                                <i class="fas fa-money-bill-wave me-1"></i>Espèces
                                                                                @break
                                                                        @endswitch
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6>Total</h6>
                                                                    <p class="mb-0 fs-5 text-primary"><strong>{{ $commande->getFormattedTotal() }}</strong></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Articles commandés -->
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5 class="mb-0">Articles commandés ({{ $commande->items->count() }})</h5>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            @foreach($commande->items as $item)
                                                                <div class="border-bottom p-3">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-2">
                                                                            @if($item->piece->photos && count($item->piece->photos) > 0)
                                                                                <img src="{{ Storage::url($item->piece->photos[0]) }}"
                                                                                     class="img-fluid rounded" alt="{{ $item->piece->nom }}"
                                                                                     style="height: 80px; object-fit: cover;">
                                                                            @else
                                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                                                    <i class="fas fa-image text-muted"></i>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <h6 class="mb-1">{{ $item->piece->nom }}</h6>
                                                                            <p class="mb-1 text-muted">
                                                                                {{ $item->piece->vehicle->marque }} {{ $item->piece->vehicle->modele }} ({{ $item->piece->vehicle->annee }})
                                                                            </p>
                                                                            <p class="mb-0">
                                                                                <small class="text-muted">
                                                                                    <i class="fas fa-store me-1"></i>{{ $item->piece->vehicle->casse->nom_entreprise ?? $item->piece->vehicle->casse->name }}
                                                                                </small>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-2 text-center">
                                                                            <span class="fs-6">{{ number_format($item->prix_unitaire, 2, ',', ' ') }}FCFA</span>
                                                                        </div>
                                                                        <div class="col-md-1 text-center">
                                                                            <span class="badge bg-light text-dark">x{{ $item->quantite }}</span>
                                                                        </div>
                                                                        <div class="col-md-2 text-end">
                                                                            <strong>{{ $item->getFormattedSousTotal() }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <!-- Suivi de commande -->
                                                    <div class="card mb-4">
                                                        <div class="card-header">
                                                            <h5 class="mb-0">Suivi de commande</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="timeline">
                                                                <div class="timeline-item {{ $commande->statut === 'en_attente' ? 'active' : 'completed' }}">
                                                                    <div class="timeline-marker"></div>
                                                                    <div class="timeline-content">
                                                                        <h6 class="mb-1">Commande reçue</h6>
                                                                        <small class="text-muted">{{ $commande->created_at->format('d/m/Y à H:i') }}</small>
                                                                    </div>
                                                                </div>

                                                                <div class="timeline-item {{ in_array($commande->statut, ['confirmee', 'en_preparation', 'expedie', 'livree']) ? ($commande->statut === 'confirmee' ? 'active' : 'completed') : '' }}">
                                                                    <div class="timeline-marker"></div>
                                                                    <div class="timeline-content">
                                                                        <h6 class="mb-1">Commande confirmée</h6>
                                                                        <small class="text-muted">
                                                                            @if(in_array($commande->statut, ['confirmee', 'en_preparation', 'expedie', 'livree']))
                                                                                {{ $commande->updated_at->format('d/m/Y à H:i') }}
                                                                            @else
                                                                                En attente
                                                                            @endif
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                                <div class="timeline-item {{ in_array($commande->statut, ['en_preparation', 'expedie', 'livree']) ? ($commande->statut === 'en_preparation' ? 'active' : 'completed') : '' }}">
                                                                    <div class="timeline-marker"></div>
                                                                    <div class="timeline-content">
                                                                        <h6 class="mb-1">En préparation</h6>
                                                                        <small class="text-muted">
                                                                            @if(in_array($commande->statut, ['en_preparation', 'expedie', 'livree']))
                                                                                En cours
                                                                            @else
                                                                                En attente
                                                                            @endif
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                                <div class="timeline-item {{ in_array($commande->statut, ['expedie', 'livree']) ? ($commande->statut === 'expedie' ? 'active' : 'completed') : '' }}">
                                                                    <div class="timeline-marker"></div>
                                                                    <div class="timeline-content">
                                                                        <h6 class="mb-1">Expédiée</h6>
                                                                        <small class="text-muted">
                                                                            @if(in_array($commande->statut, ['expedie', 'livree']))
                                                                                En transit
                                                                            @else
                                                                                En attente
                                                                            @endif
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                                <div class="timeline-item {{ $commande->statut === 'livree' ? 'completed' : '' }}">
                                                                    <div class="timeline-marker"></div>
                                                                    <div class="timeline-content">
                                                                        <h6 class="mb-1">Livrée</h6>
                                                                        <small class="text-muted">
                                                                            @if($commande->statut === 'livree')
                                                                                {{ $commande->updated_at->format('d/m/Y à H:i') }}
                                                                            @else
                                                                                En attente
                                                                            @endif
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5 class="mb-0">Actions</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="d-grid gap-2">
                                                                @if($commande->canBeAnnulee() && auth()->user()->id === $commande->user_id)
                                                                    <button type="button" class="btn btn-outline-danger" onclick="cancelOrder()">
                                                                        <i class="fas fa-times me-2"></i>Annuler la commande
                                                                    </button>
                                                                @endif

                                                                <a href="{{ route('commandes.index') }}" class="btn btn-outline-primary">
                                                                    <i class="fas fa-list me-2"></i>Mes commandes
                                                                </a>

                                                                @if($commande->statut === 'livree' && auth()->user()->isClient())
                                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                                                        <i class="fas fa-star me-2"></i>Laisser un avis
                                                                    </button>
                                                                @endif

                                                                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                                                                    <i class="fas fa-print me-2"></i>Imprimer
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal de mise à jour du statut (pour les casses) -->
                                        @auth
                                            @if(auth()->user()->isCasse())
                                                <div class="modal fade" id="updateStatusModal" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('commandes.update-statut', $commande) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Mettre à jour le statut</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="statut" class="form-label">Nouveau statut</label>
                                                                        <select name="statut" id="statut" class="form-select" required>
                                                                            <option value="en_attente" {{ $commande->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                                            <option value="confirmee" {{ $commande->statut === 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                                                            <option value="en_preparation" {{ $commande->statut === 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                                                                            <option value="expedie" {{ $commande->statut === 'expedie' ? 'selected' : '' }}>Expédiée</option>
                                                                            <option value="livree" {{ $commande->statut === 'livree' ? 'selected' : '' }}>Livrée</option>
                                                                            <option value="annulee" {{ $commande->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
                                                                        <textarea name="commentaire" id="commentaire" class="form-control" rows="3"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endauth

                                        @push('styles')
                                            <style>
                                                .timeline {
                                                    position: relative;
                                                    padding-left: 30px;
                                                }

                                                .timeline::before {
                                                    content: '';
                                                    position: absolute;
                                                    left: 15px;
                                                    top: 0;
                                                    bottom: 0;
                                                    width: 2px;
                                                    background: #e9ecef;
                                                }

                                                .timeline-item {
                                                    position: relative;
                                                    margin-bottom: 30px;
                                                }

                                                .timeline-marker {
                                                    position: absolute;
                                                    left: -22px;
                                                    top: 0;
                                                    width: 14px;
                                                    height: 14px;
                                                    border-radius: 50%;
                                                    background: #e9ecef;
                                                    border: 3px solid #fff;
                                                    box-shadow: 0 0 0 3px #e9ecef;
                                                }

                                                .timeline-item.active .timeline-marker {
                                                    background: #007bff;
                                                    box-shadow: 0 0 0 3px #007bff;
                                                }

                                                .timeline-item.completed .timeline-marker {
                                                    background: #28a745;
                                                    box-shadow: 0 0 0 3px #28a745;
                                                }                        <div class="col-md-3">
                                                <select name="marque" class="form-select form-select-lg">
                                                <option value="">Toutes marques</option>
                                                <option value="Peugeot">Peugeot</option>
                                                <option value="Renault">Renault</option>
                                                <option value="Toyota">Toyota</option>
                                                <option value="Nissan">Nissan</option>
                                                <option value="Hyundai">Hyundai</option>
                                                <option value="Kia">Kia</option>
                                                <option value="Ford">Ford</option>
                                                <option value="Volkswagen">Volkswagen</option>
                                                </select>
                                                </div>
                                                <div class="col-md-2">
                                                <input type="number" name="annee" class="form-control form-control-lg" placeholder="Année" min="1990" max="{{ date('Y') }}">
                                                </div>
                                                <div class="col-md-3">
                                                <button type="submit" class="btn btn-success btn-lg w-100">
                                                <i class="fas fa-search me-2"></i>Rechercher
                                                </button>
                                                </div>
                                                </div>
                                                </form>
                                                </div>
                                                <div class="col-lg-6 text-center">
                                                <img src="{{ asset('images/hero-car.png') }}" alt="Voiture" class="img-fluid" style="max-height: 400px;">
                                                </div>
                                                </div>
                                                </div>
                                                </section>

                                                <!-- Statistiques -->
                                                <section class="py-5 bg-light">
                                                <div class="container">
                                                <div class="row text-center">
                                                <div class="col-md-3 mb-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <i class="fas fa-cogs fa-3x text-primary mb-3"></i>
                                                <h3 class="text-primary">{{ number_format(\App\Models\Piece::count()) }}</h3>
                                                <p class="text-muted">Pièces disponibles</p>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="col-md-3 mb-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <i class="fas fa-car fa-3x text-success mb-3"></i>
                                                <h3 class="text-success">{{ number_format(\App\Models\Vehicle::count()) }}</h3>
                                                <p class="text-muted">Véhicules traités</p>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="col-md-3 mb-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <i class="fas fa-store fa-3x text-info mb-3"></i>
                                                <h3 class="text-info">{{ number_format(\App\Models\User::where('role', 'casse')->count()) }}</h3>
                                                <p class="text-muted">Casses partenaires</p>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="col-md-3 mb-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <i class="fas fa-users fa-3x text-warning mb-3"></i>
                                                <h3 class="text-warning">{{ number_format(\App\Models\User::where('role', 'client')->count()) }}</h3>
                                                <p class="text-muted">Clients satisfaits</p>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </section>

                                                <!-- Pièces populaires -->
                                                <section class="py-5">
                                                <div class="container">
                                                <div class="row mb-4">
                                                <div class="col-12 text-center">
                                                <h2 class="display-6 fw-bold">Pièces populaires</h2>
                                                <p class="lead text-muted">Les pièces les plus recherchées par nos clients</p>
                                                </div>
                                                </div>
                                                <div class="row">
                                                @php
                $piecesPopulaires = \App\Models\Piece::where('disponible', true)
                    ->withCount('commandeItems')
                    ->orderBy('commande_items_count', 'desc')
                    ->take(8)
                    ->get();
            @endphp
            @forelse($piecesPopulaires as $piece)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                                <div class="card h-100 card-hover border-0 shadow-sm">
                                                @if($piece->photos && count($piece->photos) > 0)
                            <img src="{{ Storage::url($piece->photos[0]) }}" class="card-img-top piece-card" alt="{{ $piece->nom }}">
                                                @else
                            <div class="card-img-top piece-card bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                                @endif
                        <div class="card-body">
                                                <h6 class="card-title">{{ $piece->nom }}</h6>
                                                <p class="card-text small text-muted">
                                                {{ $piece->vehicle->marque }} {{ $piece->vehicle->modele }}
                            </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                <strong class="text-primary">{{ number_format($piece->prix, 0, ',', ' ') }}FCFA</strong>
                                                <small class="text-muted">{{ $piece->vehicle->casse->ville }}</small>
                                                </div>
                                                </div>
                                                <div class="card-footer bg-transparent border-0">
                                                <a href="{{ route('pieces.show', $piece) }}" class="btn btn-outline-primary btn-sm w-100">
                                                                                                                                         Voir détails
                                                </a>
                                                </div>
                                                </div>
                                                </div>
                                                @empty
                <div class="col-12 text-center">
                                                <p class="text-muted">Aucune pièce disponible pour le moment.</p>
                                                </div>
                                                @endforelse
        </div>
                                                <div class="text-center mt-4">
                                                <a href="{{ route('pieces.index') }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-eye me-2"></i>Voir toutes les pièces
                                                </a>
                                                </div>
                                                </div>
                                                </section>

                                                <!-- Comment ça marche -->
                                                <section class="py-5 bg-light">
                                                <div class="container">
                                                <div class="row mb-5">
                                                <div class="col-12 text-center">
                                                <h2 class="display-6 fw-bold">Comment ça marche ?</h2>
                                                <p class="lead text-muted">Trouvez vos pièces en quelques clics</p>
                                                </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-4 mb-4 text-center">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                                <i class="fas fa-search fa-2x"></i>
                                                </div>
                                                <h5>1. Recherchez</h5>
                                                <p class="text-muted">Utilisez notre moteur de recherche pour trouver la pièce dont vous avez besoin selon votre véhicule.</p>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="col-md-4 mb-4 text-center">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                                <i class="fas fa-shopping-cart fa-2x"></i>
                                                </div>
                                                <h5>2. Commandez</h5>
                                                <p class="text-muted">Ajoutez les pièces à votre panier et passez commande directement auprès de la casse automobile.</p>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="col-md-4 mb-4 text-center">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                                <i class="fas fa-truck fa-2x"></i>
                                                </div>
                                                <h5>3. Recevez</h5>
                                                <p class="text-muted">Récupérez votre commande directement chez la casse ou optez pour la livraison à domicile.</p>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </section>

                                                <!-- CTA Section -->
                                                <section class="py-5 bg-primary text-white">
                                                <div class="container text-center">
                                                <div class="row">
                                                <div class="col-lg-8 mx-auto">
                                                <h2 class="display-6 fw-bold mb-3">Vous êtes une casse automobile ?</h2>
                                                <p class="lead mb-4">Rejoignez notre réseau et vendez vos pièces à des milliers de clients. Augmentez votre visibilité et vos ventes dès aujourd'hui.</p>
                                                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                                <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                                <i class="fas fa-user-plus me-2"></i>Devenir partenaire
                                                </a>
                                                <a href="{{ route('demandes-epaves.index') }}" class="btn btn-outline-light btn-lg">
                                                <i class="fas fa-handshake me-2"></i>Voir les demandes d'épaves
                                                </a>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </section>

                                                <!-- Témoignages -->
                                                <section class="py-5">
                                                <div class="container">
                                                <div class="row mb-5">
                                                <div class="col-12 text-center">
                                                <h2 class="display-6 fw-bold">Ce que disent nos clients</h2>
                                                <p class="lead text-muted">Des milliers de clients satisfaits nous font confiance</p>
                                                </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-4 mb-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <div class="d-flex mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-warning"></i>
                                                @endfor
                        </div>
                                                <p class="card-text">"J'ai trouvé rapidement la pièce que je cherchais pour ma Peugeot 206. Le service est excellent et les prix très compétitifs."</p>
                                                <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                <h6 class="mb-0">Kofi A.</h6>
                                                <small class="text-muted">Client depuis 2 ans</small>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="col-md-4 mb-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <div class="d-flex mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-warning"></i>
                                                @endfor
                        </div>
                                                <p class="card-text">"En tant que garagiste, cette plateforme me fait gagner un temps précieux. Je trouve toujours ce dont j'ai besoin."</p>
                                                <div class="d-flex align-items-center">
                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                <h6 class="mb-0">Ama S.</h6>
                                                <small class="text-muted">Garagiste</small>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="col-md-4 mb-4">
                                                <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                <div class="d-flex mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-warning"></i>
                                                @endfor
                        </div>
                                                <p class="card-text">"Service rapide et fiable. J'ai pu vendre mon épave à un bon prix grâce aux offres reçues sur la plateforme."</p>
                                                <div class="d-flex align-items-center">
                                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                <h6 class="mb-0">Kwame D.</h6>
                                                <small class="text-muted">Particulier</small>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </section>
                                                @endsection

<?php
// resources/views/panier/index.blade.php
?>
@extends('layouts.app')

@section('title', 'Mon panier')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mon panier</li>
                                                @endsection

@section('content')
<div class="container">
                                                <div class="row">
                                                <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h1><i class="fas fa-shopping-cart me-2"></i>Mon panier</h1>
                                                @if($panier && $panier->items->count() > 0)
                    <button type="button" class="btn btn-outline-danger" onclick="clearCart()">
                                                <i class="fas fa-trash me-2"></i>Vider le panier
                                                </button>
                                                @endif
            </div>
                                                </div>
                                                </div>

                                                @if($panier && $panier->items->count() > 0)
        <div class="row">
                                                <div class="col-lg-8">
                                                <div class="card">
                                                <div class="card-header">
                                                <h5 class="mb-0">Articles dans votre panier ({{ $panier->items->count() }})</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                @foreach($panier->items as $item)
                            <div class="border-bottom p-3" id="item-{{ $item->id }}">
                                                <div class="row align-items-center">
                                                <div class="col-md-2">
                                                @if($item->piece->photos && count($item->piece->photos) > 0)
                                            <img src="{{ Storage::url($item->piece->photos[0]) }}"
                                                class="img-fluid rounded" alt="{{ $item->piece->nom }}"
                                                style="height: 80px; object-fit: cover;">
                                                @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                                </div>
                                                @endif
                                    </div>
                                                <div class="col-md-4">
                                                <h6 class="mb-1">{{ $item->piece->nom }}</h6>
                                                <small class="text-muted">
                                                {{ $item->piece->vehicle->marque }} {{ $item->piece->vehicle->modele }} ({{ $item->piece->vehicle->annee }})<br>
                                                <i class="fas fa-store me-1"></i>{{ $item->piece->vehicle->casse->nom_entreprise ?? $item->piece->vehicle->casse->name }}
                                        </small>
                                                <br><small class="text-muted">État: <span class="badge bg-secondary">{{ ucfirst($item->piece->etat) }}</span></small>
                                                </div>
                                                <div class="col-md-2 text-center">
                                                <strong class="text-primary">{{ number_format($item->piece->prix, 2, ',', ' ') }}FCFA</strong>
                                                </div>
                                                <div class="col-md-2">
                                                <form action="{{ route('panier.update', $item) }}" method="POST" class="update-quantity-form">
                                                @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn btn-outline-secondary decrease-qty" data-max="{{ $item->piece->quantite }}">-</button>
                                                <input type="number" name="quantite" class="form-control text-center qty-input"
                                                value="{{ $item->quantite }}" min="1" max="{{ $item->piece->quantite }}"
                                                data-item-id="{{ $item->id }}">
                                                <button type="button" class="btn btn-outline-secondary increase-qty" data-max="{{ $item->piece->quantite }}">+</button>
                                                </div>
                                                <small class="text-muted">Max: {{ $item->piece->quantite }}</small>
                                                </form>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                <strong class="item-total">{{ number_format($item->getSousTotal(), 2, ',', ' ') }}FCFA</strong>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeItem({{ $item->id }})">
                                                <i class="fas fa-trash"></i>
                                                </button>
                                                </div>
                                                </div>
                                                </div>
                                                @endforeach
                    </div>
                                                </div>
                                                </div>

                                                <div class="col-lg-4">
                                                <div class="card sticky-top">
                                                <div class="card-header">
                                                <h5 class="mb-0">Récapitulatif</h5>
                                                </div>
                                                <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                <span>Sous-total ({{ $panier->items->count() }} articles)</span>
                                                <span id="subtotal">{{ number_format($panier->getTotal(), 2, ',', ' ') }}FCFA</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                <span>Livraison</span>
                                                <span class="text-muted">À calculer</span>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between mb-3">
                                                <strong>Total</strong>
                                                <strong class="text-primary" id="total">{{ number_format($panier->getTotal(), 2, ',', ' ') }}FCFA</strong>
                                                </div>

                                                <div class="d-grid gap-2">
                                                <a href="{{ route('commandes.create') }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-credit-card me-2"></i>Passer commande
                                                </a>
                                                <a href="{{ route('pieces.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-arrow-left me-2"></i>Continuer mes achats
                                                </a>
                                                </div>
                                                </div>
                                                </div>

                                                <!-- Suggestions -->
                                                <div class="card mt-4">
                                                <div class="card-header">
                                                <h6 class="mb-0">Vous pourriez aussi aimer</h6>
                                                </div>
                                                <div class="card-body">
                                                @php
                            $suggestions = \App\Models\Piece::where('disponible', true)
                                ->whereNotIn('id', $panier->items->pluck('piece_id'))
                                ->inRandomOrder()
                                ->take(3)
                                ->get();
                        @endphp
                        @foreach($suggestions as $piece)
                            <div class="d-flex align-items-center mb-3">
                                                @if($piece->photos && count($piece->photos) > 0)
                                    <img src="{{ Storage::url($piece->photos[0]) }}"
                                                class="me-3 rounded" width="50" height="50"
                                                style="object-fit: cover;" alt="{{ $piece->nom }}">
                                                @else
                                    <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                                </div>
                                                @endif
                                <div class="flex-grow-1">
                                                <h6 class="mb-0 small">{{ Str::limit($piece->nom, 25) }}</h6>
                                                <small class="text-muted">{{ $piece->prix }}FCFA</small>
                                                </div>
                                                <a href="{{ route('pieces.show', $piece) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                                </a>
                                                </div>
                                                @endforeach
                    </div>
                                                </div>
                                                </div>
                                                </div>
                                                @else
        <div class="row">
                                                <div class="col-lg-8 mx-auto text-center">
                                                <div class="card">
                                                <div class="card-body py-5">
                                                <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                                                <h3>Votre panier est vide</h3>
                                                <p class="text-muted mb-4">Découvrez nos milliers de pièces détachées disponibles</p>
                                                <a href="{{ route('pieces.index') }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-search me-2"></i>Rechercher des pièces
                                                </a>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                @endif
</div>

                                                @push('scripts')
<script>
                                                 function removeItem(itemId) {
                                                confirmDelete(() => {
                                                fetch(`/panier/items/${itemId}`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                'Content-Type': 'application/json'
                                                }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                document.getElementById(`item-${itemId}`).remove();
                                                updateCartTotals();
                                                updatePanierCount();
                                                showToast('Article retiré du panier', 'success');

                                                // Recharger la page si le panier est vide
                                                if (document.querySelectorAll('[id^="item-"]').length === 0) {
                                                window.location.reload();
                                                }
                                                }
                                                })
                                                .catch(error => {
                                                console.error('Erreur:', error);
                                                showToast('Erreur lors de la suppression', 'error');
                                                });
                                                });
                                                }

                                                function clearCart() {
                                                confirmDelete(() => {
                                                fetch('/panier/clear', {
                                                    method: 'DELETE',
                                                    headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                'Content-Type': 'application/json'
                                                }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                window.location.reload();
                                                }
                                                })
                                                .catch(error => {
                                                console.error('Erreur:', error);
                                                showToast('Erreur lors du vidage du panier', 'error');
                                                });
                                                });
                                                }

                                                function updateQuantity(itemId, newQuantity) {
                                                    const formData = new FormData();
                                                formData.append('quantite', newQuantity);
                                                formData.append('_method', 'PUT');

                                                fetch(`/panier/items/${itemId}`, {
                                                    method: 'POST',
                                                    headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                },
                                                body: formData
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                updateCartTotals();
                                                updatePanierCount();
                                                }
                                                })
                                                .catch(error => {
                                                console.error('Erreur:', error);
                                                showToast('Erreur lors de la mise à jour', 'error');
                                                });
                                                }

                                                function updateCartTotals() {
                                                    let total = 0;
                                                document.querySelectorAll('[id^="item-"]').forEach(item => {
                                                    const qtyInput = item.querySelector('.qty-input');
                                                    const priceText = item.querySelector('.text-primary').textContent;
                                                    const price = parseFloat(priceText.replace(/[^\d,.-]/g, '').replace(',', '.'));
                                                    const quantity = parseInt(qtyInput.value);
                                                    const itemTotal = price * quantity;

                                                item.querySelector('.item-total').textContent = new Intl.NumberFormat('fr-FR', {
                                                    style: 'currency',
                                                    currency: 'EUR'
                                                }).format(itemTotal);

                                                    total += itemTotal;
                                                });

                                                document.getElementById('subtotal').textContent = new Intl.NumberFormat('fr-FR', {
                                                    style: 'currency',
                                                    currency: 'EUR'
                                                }).format(total);

                                                document.getElementById('total').textContent = new Intl.NumberFormat('fr-FR', {
                                                    style: 'currency',
                                                    currency: 'EUR'
                                                }).format(total);
                                                }

                                                // Gestionnaires pour les boutons +/-
                                                document.addEventListener('click', function(e) {
                                                    if (e.target.classList.contains('increase-qty')) {
                                                    const input = e.target.previousElementSibling;
                                                    const max = parseInt(e.target.dataset.max);
                                                    const current = parseInt(input.value);

                                                    if (current < max) {
                                                input.value = current + 1;
                                                    const itemId = input.dataset.itemId;
                                                updateQuantity(itemId, input.value);
                                                }
                                                }

                                                    if (e.target.classList.contains('decrease-qty')) {
                                                    const input = e.target.nextElementSibling;
                                                    const current = parseInt(input.value);

                                                    if (current > 1) {
                                                input.value = current - 1;
                                                    const itemId = input.dataset.itemId;
                                                updateQuantity(itemId, input.value);
                                                }
                                                }
                                                });

                                                // Mise à jour en temps réel des quantités
                                                   document.querySelectorAll('.qty-input').forEach(input => {
                                                                                                              input.addEventListener('change', function() {
                                                                                                              const itemId = this.dataset.itemId;
                                                                                                              const quantity = parseInt(this.value);
                                                                                                              const max = parseInt(this.max);

                                                                                                              if (quantity > max) {
                                                                                                              this.value = max;
                                                                                                          showToast(`Quantité limitée à ${max}`, 'warning');
                                                                                                          }

                                                                                                              if (quantity < 1) {
                                                                                                              this.value = 1;
                                                                                                          }

                                                                                                          updateQuantity(itemId, this.value);
                                                                                                          });
                                                                                                          });
                                                </script>
                                                @endpush
@endsection

<?php
// resources/views/commandes/create.blade.php
?>
@extends('layouts.app')

@section('title', 'Passer commande')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('panier.index') }}">Panier</a></li>
                                                <li class="breadcrumb-item active">Commande</li>
                                                @endsection

@section('content')
<div class="container">
                                                <div class="row">
                                                <div class="col-12">
                                                <h1 class="mb-4"><i class="fas fa-credit-card me-2"></i>Finaliser ma commande</h1>
                                                </div>
                                                </div>

                                                <form action="{{ route('commandes.store') }}" method="POST" id="checkout-form">
                                                @csrf
        <div class="row">
                                                <div class="col-lg-8">
                                                <!-- Informations de livraison -->
                                                <div class="card mb-4">
                                                <div class="card-header">
                                                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Informations de livraison</h5>
                                                </div>
                                                <div class="card-body">
                                                <div class="row">
                                                <?php
// resources/views/layouts/app.blade.php
?>
<!DOCTYPE html>
                                                <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
                                                <head>
                                                <meta charset="utf-8">
                                                <meta name="viewport" content="width=device-width, initial-scale=1">
                                                <meta name="csrf-token" content="{{ csrf_token() }}">

                                                <title>@yield('title', config('app.name', 'Casse Auto'))</title>

                                                <!-- Favicons -->
                                                <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

                                                <!-- CSS -->
                                                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                                                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
                                                <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">

                                                @vite(['resources/css/app.css'])

    <style>
                                                     .navbar-brand img {
                                                         height: 40px;
                                                     }
                                                .card-hover:hover {
                                                    transform: translateY(-5px);
                                                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                                                    transition: all 0.3s ease;
                                                }
                                                .btn-floating {
                                                    position: fixed;
                                                    bottom: 20px;
                                                    right: 20px;
                                                    width: 60px;
                                                    height: 60px;
                                                    border-radius: 50%;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    z-index: 1000;
                                                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                                }
                                                .notification-dropdown {
                                                    max-height: 400px;
                                                    overflow-y: auto;
                                                }
                                                .piece-card img {
                                                    height: 200px;
                                                    object-fit: cover;
                                                }
                                                .vehicle-card img {
                                                    height: 250px;
                                                    object-fit: cover;
                                                }
                                                .loading {
                                                    display: none;
                                                }
                                                .spinner-border-sm {
                                                    width: 1rem;
                                                    height: 1rem;
                                                }
                                                @media (max-width: 768px) {
                                                    .btn-floating {
                                                        bottom: 15px;
                                                        right: 15px;
                                                        width: 50px;
                                                        height: 50px;
                                                    }
                                                }
                                            </style>

                                            @stack('styles')
                                            </head>
                                            <body class="bg-light">
                                            <!-- Navigation -->
                                            <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
                                                <div class="container">
                                                    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                                                        <i class="fas fa-car me-2"></i>
                                                        <span>{{ config('app.name', 'Casse Auto') }}</span>
                                                    </a>

                                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                                                        <span class="navbar-toggler-icon"></span>
                                                    </button>

                                                    <div class="collapse navbar-collapse" id="navbarNav">
                                                        <ul class="navbar-nav me-auto">
                                                            <li class="nav-item">
                                                                <a class="nav-link {{ request()->routeIs('pieces.*') ? 'active' : '' }}" href="{{ route('pieces.index') }}">
                                                                    <i class="fas fa-cogs me-1"></i> Pièces détachées
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">
                                                                    <i class="fas fa-car me-1"></i> Véhicules
                                                                </a>
                                                            </li>
                                                            @auth
                                                                @if(auth()->user()->isClient())
                                                                    <li class="nav-item">
                                                                        <a class="nav-link {{ request()->routeIs('demandes-epaves.*') ? 'active' : '' }}" href="{{ route('demandes-epaves.index') }}">
                                                                            <i class="fas fa-handshake me-1"></i> Vendre mon épave
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(auth()->user()->isCasse())
                                                                    <li class="nav-item dropdown">
                                                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                                                            <i class="fas fa-tools me-1"></i> Gestion
                                                                        </a>
                                                                        <ul class="dropdown-menu">
                                                                            <li><a class="dropdown-item" href="{{ route('vehicles.index') }}">Mes véhicules</a></li>
                                                                            <li><a class="dropdown-item" href="{{ route('pieces.index') }}">Mes pièces</a></li>
                                                                            <li><a class="dropdown-item" href="{{ route('commandes.index') }}">Commandes reçues</a></li>
                                                                            <li><hr class="dropdown-divider"></li>
                                                                            <li><a class="dropdown-item" href="{{ route('demandes-epaves.index') }}">Demandes d'épaves</a></li>
                                                                        </ul>
                                                                    </li>
                                                                @endif
                                                            @endauth
                                                        </ul>

                                                        <!-- Barre de recherche -->
                                                        <form class="d-flex me-3" action="{{ route('search') }}" method="GET">
                                                            <div class="input-group">
                                                                <input class="form-control" type="search" name="q" placeholder="Rechercher..."
                                                                       value="{{ request('q') }}" style="min-width: 250px;" id="search-input">
                                                                <button class="btn btn-outline-light" type="submit">
                                                                    <i class="fas fa-search"></i>
                                                                </button>
                                                            </div>
                                                            <div id="search-suggestions" class="position-absolute bg-white border rounded mt-5 w-100 d-none" style="z-index: 1050;"></div>
                                                        </form>

                                                        <ul class="navbar-nav">
                                                            @auth
                                                                <!-- Panier pour les clients -->
                                                                @if(auth()->user()->isClient())
                                                                    <li class="nav-item">
                                                                        <a class="nav-link position-relative" href="{{ route('panier.index') }}">
                                                                            <i class="fas fa-shopping-cart"></i>
                                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="panier-count">
                                        {{ auth()->user()->panier->items->sum('quantite') }}
                                    </span>
                                                                        </a>
                                                                    </li>
                                                                @endif

                                                                <!-- Notifications -->
                                                                <li class="nav-item dropdown">
                                                                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown"
                                                                       role="button" data-bs-toggle="dropdown">
                                                                        <i class="fas fa-bell"></i>
                                                                        @if(auth()->user()->notifications()->where('lu', false)->count() > 0)
                                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifications-count">
                                        {{ auth()->user()->notifications()->where('lu', false)->count() }}
                                    </span>
                                                                        @endif
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px;">
                                                                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                                                                            <span>Notifications</span>
                                                                            @if(auth()->user()->notifications()->where('lu', false)->count() > 0)
                                                                                <a href="{{ route('notifications.read-all') }}" class="btn btn-sm btn-link p-0">
                                                                                    Tout marquer lu
                                                                                </a>
                                                                            @endif
                                                                        </li>
                                                                        @forelse(auth()->user()->notifications()->where('lu', false)->latest()->take(5)->get() as $notification)
                                                                            <li>
                                                                                <a class="dropdown-item py-2" href="{{ route('notifications.read', $notification) }}">
                                                                                    <div class="d-flex">
                                                                                        <div class="flex-shrink-0 me-2">
                                                                                            <i class="{{ $notification->type_icon }} text-{{ $notification->type === 'commande' ? 'primary' : 'info' }}"></i>
                                                                                        </div>
                                                                                        <div class="flex-grow-1">
                                                                                            <strong class="d-block">{{ $notification->titre }}</strong>
                                                                                            <small class="text-muted">{{ Str::limit($notification->message, 60) }}</small>
                                                                                            <br><small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                            </li>
                                                                            @if(!$loop->last)<li><hr class="dropdown-divider my-1"></li>@endif
                                                                        @empty
                                                                            <li><span class="dropdown-item text-muted text-center py-3">Aucune notification</span></li>
                                                                        @endforelse
                                                                        @if(auth()->user()->notifications()->where('lu', false)->count() > 0)
                                                                            <li><hr class="dropdown-divider"></li>
                                                                            <li>
                                                                                <a class="dropdown-item text-center py-2" href="{{ route('notifications.index') }}">
                                                                                    <i class="fas fa-eye me-1"></i> Voir toutes les notifications
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                </li>

                                                                <!-- Menu utilisateur -->
                                                                <li class="nav-item dropdown">
                                                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                                                        @if(auth()->user()->logo)
                                                                            <img src="{{ auth()->user()->logo_url }}" alt="Logo" class="rounded-circle me-2" width="30" height="30">
                                                                        @else
                                                                            <i class="fas fa-user me-2"></i>
                                                                        @endif
                                                                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                        <li class="dropdown-header">
                                                                            <div class="d-flex align-items-center">
                                                                                <div>
                                                                                    <strong>{{ auth()->user()->name }}</strong><br>
                                                                                    <small class="text-muted">{{ auth()->user()->role->label() }}</small>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <li><hr class="dropdown-divider"></li>
                                                                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                                                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                                                            </a></li>
                                                                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                                                                <i class="fas fa-user me-2"></i> Mon profil
                                                                            </a></li>
                                                                        @if(auth()->user()->isClient())
                                                                            <li><a class="dropdown-item" href="{{ route('commandes.index') }}">
                                                                                    <i class="fas fa-shopping-bag me-2"></i> Mes commandes
                                                                                </a></li>
                                                                            <li><a class="dropdown-item" href="{{ route('favoris.index') }}">
                                                                                    <i class="fas fa-heart me-2"></i> Mes favoris
                                                                                </a></li>
                                                                        @endif
                                                                        @if(auth()->user()->isAdmin())
                                                                            <li><hr class="dropdown-divider"></li>
                                                                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                                                                    <i class="fas fa-users me-2"></i> Gestion utilisateurs
                                                                                </a></li>
                                                                            <li><a class="dropdown-item" href="{{ route('admin.statistics') }}">
                                                                                    <i class="fas fa-chart-bar me-2"></i> Statistiques
                                                                                </a></li>
                                                                        @endif
                                                                        <li><hr class="dropdown-divider"></li>
                                                                        <li>
                                                                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                                                                @csrf
                                                                                <button type="submit" class="dropdown-item">
                                                                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                                                                </button>
                                                                            </form>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            @else
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="{{ route('login') }}">
                                                                        <i class="fas fa-sign-in-alt me-1"></i> Connexion
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="{{ route('register') }}">
                                                                        <i class="fas fa-user-plus me-1"></i> Inscription
                                                                    </a>
                                                                </li>
                                                            @endauth
                                                        </ul>
                                                    </div>
                                                </div>
                                            </nav>

                                            <!-- Breadcrumb -->
                                            @if(!request()->routeIs('home') && !request()->routeIs('dashboard'))
                                                <nav aria-label="breadcrumb" class="bg-white border-bottom">
                                                    <div class="container">
                                                        <ol class="breadcrumb py-2 mb-0">
                                                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                                                            @yield('breadcrumb')
                                                        </ol>
                                                    </div>
                                                </nav>
                                            @endif

                                            <!-- Messages Flash -->
                                            @if (session('success') || session('error') || session('warning') || session('info'))
                                                <div class="container mt-3">
                                                    @if (session('success'))
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                        </div>
                                                    @endif
                                                    @if (session('error'))
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                        </div>
                                                    @endif
                                                    @if (session('warning'))
                                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                        </div>
                                                    @endif
                                                    @if (session('info'))
                                                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Contenu principal -->
                                            <main class="py-4">
                                                @yield('content')
                                            </main>

                                            <!-- Bouton flottant pour actions rapides -->
                                            @auth
                                                @if(auth()->user()->isCasse())
                                                    <div class="btn-group dropup">
                                                        <button type="button" class="btn btn-primary btn-floating" data-bs-toggle="dropdown">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end mb-2">
                                                            <li><a class="dropdown-item" href="{{ route('vehicles.create') }}">
                                                                    <i class="fas fa-car me-2"></i> Ajouter véhicule
                                                                </a></li>
                                                            <li><a class="dropdown-item" href="{{ route('pieces.create') }}">
                                                                    <i class="fas fa-cog me-2"></i> Ajouter pièce
                                                                </a></li>
                                                        </ul>
                                                    </div>
                                                @elseif(auth()->user()->isClient())
                                                    <a href="{{ route('panier.index') }}" class="btn btn-success btn-floating">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </a>
                                                @endif
                                            @endauth

                                            <!-- Footer -->
                                            <footer class="bg-dark text-white mt-5">
                                                <div class="container py-5">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-4">
                                                            <h5><i class="fas fa-car me-2"></i>{{ config('app.name') }}</h5>
                                                            <p>Plateforme de gestion et vente de pièces détachées automobile. Trouvez facilement les pièces dont vous avez besoin.</p>
                                                            <div class="d-flex">
                                                                <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                                                                <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                                                                <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                                                                <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 mb-4">
                                                            <h6>Navigation</h6>
                                                            <ul class="list-unstyled">
                                                                <li><a href="{{ route('pieces.index') }}" class="text-white-50">Pièces</a></li>
                                                                <li><a href="{{ route('vehicles.index') }}" class="text-white-50">Véhicules</a></li>
                                                                <li><a href="{{ route('search') }}" class="text-white-50">Recherche</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-2 mb-4">
                                                            <h6>Services</h6>
                                                            <ul class="list-unstyled">
                                                                <li><a href="#" class="text-white-50">Vente d'épaves</a></li>
                                                                <li><a href="#" class="text-white-50">Livraison</a></li>
                                                                <li><a href="#" class="text-white-50">Support</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-2 mb-4">
                                                            <h6>Légal</h6>
                                                            <ul class="list-unstyled">
                                                                <li><a href="#" class="text-white-50">CGU</a></li>
                                                                <li><a href="#" class="text-white-50">Confidentialité</a></li>
                                                                <li><a href="#" class="text-white-50">Cookies</a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-2 mb-4">
                                                            <h6>Contact</h6>
                                                            <ul class="list-unstyled text-white-50">
                                                                <li><i class="fas fa-phone me-2"></i>+228 XX XX XX XX</li>
                                                                <li><i class="fas fa-envelope me-2"></i>contact@casseauto.tg</li>
                                                                <li><i class="fas fa-map-marker-alt me-2"></i>Lomé, Togo</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <hr class="my-4">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-6">
                                                            <small>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</small>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <small>Développé avec <i class="fas fa-heart text-danger"></i> au Togo</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </footer>

                                            <!-- Scripts -->
                                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
                                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
                                            @vite(['resources/js/app.js'])

                                            <script>
                                                // Configuration globale
                                                window.App = {
                                                    url: '{{ url("/") }}',
                                                    token: '{{ csrf_token() }}',
                                                    user: @auth {!! json_encode([
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'role' => auth()->user()->role->value
            ]) !!} @else null @endauth
                                                };

                                                // Fonctions utilitaires
                                                function showLoading(element) {
                                                    element.querySelector('.loading').style.display = 'inline-block';
                                                    element.disabled = true;
                                                }

                                                function hideLoading(element) {
                                                    element.querySelector('.loading').style.display = 'none';
                                                    element.disabled = false;
                                                }

                                                function showToast(message, type = 'success') {
                                                    Swal.fire({
                                                        toast: true,
                                                        position: 'top-end',
                                                        showConfirmButton: false,
                                                        timer: 3000,
                                                        timerProgressBar: true,
                                                        icon: type,
                                                        title: message
                                                    });
                                                }

                                                function confirmDelete(callback) {
                                                    Swal.fire({
                                                        title: 'Êtes-vous sûr ?',
                                                        text: "Cette action ne peut pas être annulée !",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#d33',
                                                        cancelButtonColor: '#3085d6',
                                                        confirmButtonText: 'Oui, supprimer !',
                                                        cancelButtonText: 'Annuler'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            callback();
                                                        }
                                                    });
                                                }

                                                // Mise à jour du compteur de panier
                                                function updatePanierCount() {
                                                    @auth
                                                    @if(auth()->user()->isClient())
                                                    fetch('/api/v1/panier/count', {
                                                        headers: {
                                                            'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                        }
                                                    })
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            const badge = document.getElementById('panier-count');
                                                            if (badge) {
                                                                badge.textContent = data.count;
                                                                badge.style.display = data.count > 0 ? 'inline' : 'none';
                                                            }
                                                        })
                                                        .catch(error => console.error('Erreur:', error));
                                                    @endif
                                                    @endauth
                                                }

                                                // Mise à jour du compteur de notifications
                                                function updateNotificationsCount() {
                                                    @auth
                                                    fetch('/api/v1/notifications/unread-count', {
                                                        headers: {
                                                            'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                        }
                                                    })
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            const badge = document.getElementById('notifications-count');
                                                            if (badge) {
                                                                badge.textContent = data.count;
                                                                badge.style.display = data.count > 0 ? 'inline' : 'none';
                                                            }
                                                        })
                                                        .catch(error => console.error('Erreur:', error));
                                                    @endauth
                                                }

                                                // Autocomplétion de recherche
                                                const searchInput = document.getElementById('search-input');
                                                const searchSuggestions = document.getElementById('search-suggestions');
                                                let searchTimeout;

                                                if (searchInput) {
                                                    searchInput.addEventListener('input', function() {
                                                        clearTimeout(searchTimeout);
                                                        const query = this.value.trim();

                                                        if (query.length < 2) {
                                                            searchSuggestions.classList.add('d-none');
                                                            return;
                                                        }

                                                        searchTimeout = setTimeout(() => {
                                                            fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`)
                                                                .then(response => response.json())
                                                                .then(data => {
                                                                    if (data.length > 0) {
                                                                        searchSuggestions.innerHTML = data.map(item =>
                                                                            `<div class="p-2 border-bottom suggestion-item" style="cursor: pointer;">${item}</div>`
                                                                        ).join('');

                                                                        searchSuggestions.classList.remove('d-none');

                                                                        // Gestionnaire de clic sur les suggestions
                                                                        searchSuggestions.querySelectorAll('.suggestion-item').forEach(item => {
                                                                            item.addEventListener('click', function() {
                                                                                searchInput.value = this.textContent;
                                                                                searchSuggestions.classList.add('d-none');
                                                                                searchInput.closest('form').submit();
                                                                            });
                                                                        });
                                                                    } else {
                                                                        searchSuggestions.classList.add('d-none');
                                                                    }
                                                                })
                                                                .catch(error => {
                                                                    console.error('Erreur autocomplétion:', error);
                                                                    searchSuggestions.classList.add('d-none');
                                                                });
                                                        }, 300);
                                                    });

                                                    // Masquer les suggestions quand on clique ailleurs
                                                    document.addEventListener('click', function(e) {
                                                        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                                                            searchSuggestions.classList.add('d-none');
                                                        }
                                                    });
                                                }

                                                // Gestionnaire pour les boutons favoris
                                                document.addEventListener('click', function(e) {
                                                    if (e.target.classList.contains('btn-favorite') || e.target.closest('.btn-favorite')) {
                                                        e.preventDefault();
                                                        const button = e.target.classList.contains('btn-favorite') ? e.target : e.target.closest('.btn-favorite');
                                                        const type = button.dataset.type;
                                                        const id = button.dataset.id;

                                                        fetch(`/api/v1/favoris/${type}/${id}`, {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                            }
                                                        })
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                const icon = button.querySelector('i');
                                                                if (data.favoris) {
                                                                    icon.className = 'fas fa-heart text-danger';
                                                                    showToast('Ajouté aux favoris', 'success');
                                                                } else {
                                                                    icon.className = 'far fa-heart';
                                                                    showToast('Retiré des favoris', 'info');
                                                                }
                                                            })
                                                            .catch(error => {
                                                                console.error('Erreur:', error);
                                                                showToast('Erreur lors de la mise à jour', 'error');
                                                            });
                                                    }
                                                });

                                                // Initialisation
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    // Mettre à jour les compteurs au chargement
                                                    updatePanierCount();
                                                    updateNotificationsCount();

                                                    // Mettre à jour les compteurs périodiquement
                                                    setInterval(() => {
                                                        updatePanierCount();
                                                        updateNotificationsCount();
                                                    }, 30000); // Toutes les 30 secondes

                                                    // Auto-masquer les alertes après 5 secondes
                                                    setTimeout(() => {
                                                        document.querySelectorAll('.alert').forEach(alert => {
                                                            if (alert.querySelector('.btn-close')) {
                                                                alert.querySelector('.btn-close').click();
                                                            }
                                                        });
                                                    }, 5000);
                                                });

                                                // Géolocalisation
                                                function getLocation(callback) {
                                                    if (navigator.geolocation) {
                                                        navigator.geolocation.getCurrentPosition(callback, function(error) {
                                                            console.error('Erreur géolocalisation:', error);
                                                            showToast('Impossible d\'obtenir votre position', 'error');
                                                        });
                                                    } else {
                                                        showToast('La géolocalisation n\'est pas supportée', 'error');
                                                    }
                                                }
                                            </script>

                                            @stack('scripts')
                                            </body>
                                            </html>

                                                <?php
// resources/views/welcome.blade.php
                                                ?>
                                            @extends('layouts.app')

                                            @section('title', 'Accueil - Trouvez vos pièces automobiles')

                                            @section('content')
                                                <!-- Hero Section -->
                                                <section class="bg-primary text-white py-5 mb-5">
                                                    <div class="container">
                                                        <div class="row align-items-center">
                                                            <div class="col-lg-6">
                                                                <h1 class="display-4 fw-bold mb-4">Trouvez vos pièces automobiles facilement</h1>
                                                                <p class="lead mb-4">La plus grande plateforme de pièces détachées d'occasion au Togo. Des milliers de pièces disponibles auprès de casses automobiles partenaires.</p>

                                                                <!-- Formulaire de recherche principal -->
                                                                <form action="{{ route('search') }}" method="GET" class="bg-white p-4 rounded shadow">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-4">
                                                                            <input type="text" name="q" class="form-control form-control-lg" placeholder="Pièce recherchée..." value="{{ request('q') }}">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select name
