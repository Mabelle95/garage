@extends('layouts.app')

@section('title', auth()->user()->isCasse() ? 'Gestion des pièces détachées' : 'Rechercher des pièces détachées')

@section('content')
    <div class="container-fluid">

        {{-- Titre et bouton Ajouter --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                {{ auth()->user()->isCasse() ? 'Gestion des pièces détachées' : 'Rechercher des pièces détachées' }}
            </h1>
            @if(auth()->user()->isCasse())
                <a href="{{ route('pieces.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter une pièce
                </a>
            @endif
        </div>

        {{-- Filtres --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Nom de la pièce..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="marque_id" class="form-select" id="filterMarque">
                            <option value="">Toutes marques</option>
                            @foreach($marques as $marque)
                                <option value="{{ $marque->id }}" {{ request('marque_id') == $marque->id ? 'selected' : '' }}>
                                    {{ $marque->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="etat" class="form-select">
                            <option value="">Tous états</option>
                            @foreach(['neuf','tres_bon','bon','moyen','usage'] as $etat)
                                <option value="{{ $etat }}" {{ request('etat') == $etat ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $etat)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="ville" class="form-select">
                            <option value="">Toutes villes</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                    {{ $ville }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('pieces.index') }}" class="btn btn-secondary w-100" title="Réinitialiser">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Statistiques rapides (pour les casses seulement) --}}
        @if(auth()->user()->isCasse() && $pieces->total() > 0)
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pièces</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pieces->total() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-cogs fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Disponibles</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $pieces->where('disponible', true)->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Stock Total</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $pieces->sum('quantite') }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Marques</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $marques->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tags fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Liste des pièces --}}
        <div class="card shadow">
            <div class="card-body">
                @if($pieces->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Photo</th>
                                <th>Pièce</th>
                                <th>Marque</th>
                                <th>Modèle</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pieces as $piece)
                                <tr>
                                    <td>
                                        @if($piece->photos && count($piece->photos) > 0)
                                            <img src="{{ asset('storage/' . $piece->photos[0]) }}"
                                                 width="60"
                                                 height="60"
                                                 class="rounded"
                                                 style="object-fit: cover;"
                                                 alt="{{ $piece->nom }}">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-cog text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $piece->nom }}</strong><br>
                                        @if($piece->reference_constructeur)
                                            <small class="text-muted">Réf: {{ $piece->reference_constructeur }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($piece->marque)
                                            <span class="badge bg-primary">{{ $piece->marque->nom }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($piece->modele)
                                            <span class="badge bg-secondary">{{ $piece->modele->nom }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ number_format($piece->prix, 0, ',', ' ') }}</strong> FCFA
                                    </td>
                                    <td>
                                        @if($piece->quantite > 10)
                                            <span class="badge bg-success">{{ $piece->quantite }}</span>
                                        @elseif($piece->quantite > 0)
                                            <span class="badge bg-warning text-dark">{{ $piece->quantite }}</span>
                                        @else
                                            <span class="badge bg-danger">Rupture</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst(str_replace('_', ' ', $piece->etat)) }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pieces.show', $piece) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(auth()->user()->isClient())
                                                @if($piece->disponible && $piece->quantite > 0)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-success add-to-cart-btn"
                                                            data-piece="{{ $piece->id }}"
                                                            title="Ajouter au panier">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button>
                                                    {{-- <button type="button" class="btn btn-sm btn-outline-success add-to-cart-btn"
                                                            data-piece-id="{{ $piece->id }}" data-piece-name="{{ $piece->nom }}"
                                                            data-piece-max="{{ $piece->quantite }}" title="Ajouter au panier">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button> --}}
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-secondary"
                                                            disabled
                                                            title="Indisponible">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif
                                            @endif

                                            @if(auth()->user()->isCasse() && $piece->user_id === auth()->id())
                                                <a href="{{ route('pieces.edit', $piece) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pieces.destroy', $piece) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette pièce ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Affichage de {{ $pieces->firstItem() }} à {{ $pieces->lastItem() }} sur {{ $pieces->total() }} pièces
                        </div>
                        <div>
                            {{ $pieces->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-cog fa-4x text-muted mb-3"></i>
                        <h4>Aucune pièce trouvée</h4>
                        @if(auth()->user()->isCasse())
                            @if(request()->hasAny(['search', 'marque_id', 'etat', 'ville']))
                                <p class="text-muted">Aucune pièce ne correspond à vos critères de recherche.</p>
                                <a href="{{ route('pieces.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Réinitialiser les filtres
                                </a>
                            @else
                                <p class="text-muted">Commencez par ajouter votre première pièce</p>
                                <a href="{{ route('pieces.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter une pièce
                                </a>
                            @endif
                        @else
                            <p class="text-muted">Aucune pièce ne correspond à vos critères de recherche.</p>
                            <a href="{{ route('pieces.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Voir toutes les pièces
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Modal pour sélectionner la quantité --}}
    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addToCartModalLabel">
                        <i class="fas fa-cart-plus"></i> Ajouter au panier
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addToCartForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Pièce: <strong id="modal-piece-name"></strong></p>
                        <div class="mb-3">
                            <label for="modal-quantite" class="form-label">Quantité</label>
                            <input type="number"
                                   name="quantite"
                                   id="modal-quantite"
                                   class="form-control"
                                   value="1"
                                   min="1"
                                   required>
                            <small class="text-muted">Maximum disponible: <span id="modal-max-quantity"></span></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addToCartModal = new bootstrap.Modal(document.getElementById('addToCartModal'));
            const addToCartForm = document.getElementById('addToCartForm');
            const modalQuantiteInput = document.getElementById('modal-quantite');
            const modalPieceName = document.getElementById('modal-piece-name');
            const modalMaxQuantity = document.getElementById('modal-max-quantity');

            // Gestion de l'ajout au panier avec modal
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

            addToCartButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const pieceId = this.getAttribute('data-piece-id');
                    const pieceName = this.getAttribute('data-piece-name');
                    const pieceMax = this.getAttribute('data-piece-max');

                    // Configurer le modal
                    modalPieceName.textContent = pieceName;
                    modalMaxQuantity.textContent = pieceMax;
                    modalQuantiteInput.value = 1;
                    modalQuantiteInput.max = pieceMax;

                    // Configurer l'URL du formulaire
                    let url = '{{ route("panier.add", ["piece" => ":piece"]) }}';
                    url = url.replace(':piece', pieceId);
                    addToCartForm.action = url;

                    // Afficher le modal
                    addToCartModal.show();
                });
            });

            // Validation de la quantité dans le modal
            modalQuantiteInput.addEventListener('input', function() {
                const max = parseInt(this.max);
                const value = parseInt(this.value);

                if (value > max) {
                    this.value = max;
                    showAlert('Quantité maximale disponible: ' + max, 'warning');
                }

                if (value < 1) {
                    this.value = 1;
                }
            });

            // Soumission du formulaire
            addToCartForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;

                // Désactiver le bouton
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout...';

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('Pièce ajoutée au panier avec succès !', 'success');
                            addToCartModal.hide();
                            updateCartCount();
                        } else {
                            showAlert(data.message || 'Erreur lors de l\'ajout au panier', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showAlert('Erreur lors de l\'ajout au panier', 'danger');
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    });
            });

            // Fonction pour afficher les alertes
            function showAlert(message, type) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
                alertDiv.style.zIndex = '9999';
                alertDiv.style.minWidth = '300px';
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);
            }

            // Fonction pour mettre à jour le compteur du panier
            function updateCartCount() {
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    fetch('{{ route("panier.index") }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.count !== undefined) {
                                cartCountElement.textContent = data.count;
                                cartCountElement.classList.remove('d-none');
                            }
                        })
                        .catch(error => console.error('Erreur lors de la mise à jour du compteur:', error));
                }
            }
        });
    </script>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .btn-group .btn {
            margin: 0 2px;
        }

        .badge {
            font-weight: 500;
        }

        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
    </style>
@endsection
