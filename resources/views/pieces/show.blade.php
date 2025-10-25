@extends('layouts.app')

@section('title', $piece->nom)

@section('content')
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pieces.index') }}">Pièces</a></li>
                <li class="breadcrumb-item active">{{ $piece->nom }}</li>
            </ol>
        </nav>

        <div class="row">
            {{-- Colonne principale --}}
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        {{-- Photos --}}
                        @if($piece->photos && count($piece->photos) > 0)
                            <div id="pieceCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($piece->photos as $index => $photo)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $photo) }}"
                                                 class="d-block w-100 rounded"
                                                 style="max-height: 400px; object-fit: contain;"
                                                 alt="{{ $piece->nom }}">
                                        </div>
                                    @endforeach
                                </div>
                                @if(count($piece->photos) > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#pieceCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Précédent</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#pieceCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Suivant</span>
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-4" style="height: 300px;">
                                <i class="fas fa-cog fa-5x text-muted"></i>
                            </div>
                        @endif

                        {{-- Titre et badges --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="mb-2">{{ $piece->nom }}</h2>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($piece->marque)
                                        <span class="badge bg-primary">
                                            <i class="fas fa-tag"></i> {{ $piece->marque->nom }}
                                        </span>
                                    @endif
                                    @if($piece->modele)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-car"></i> {{ $piece->modele->nom }}
                                        </span>
                                    @endif
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $piece->etat)) }}
                                    </span>
                                    @if($piece->disponible)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Disponible
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Indisponible
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $piece->description }}</p>
                        </div>

                        {{-- Informations techniques --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Référence constructeur</h6>
                                <p><code>{{ $piece->reference_constructeur }}</code></p>
                            </div>
                            <div class="col-md-6">
                                <h6>Compatible avec</h6>
                                <p>{{ $piece->compatible_avec }}</p>
                            </div>
                        </div>

                        {{-- Actions pour casse --}}
                        @if(auth()->user()->isCasse() && $piece->user_id === auth()->id())
                            <div class="d-flex gap-2">
                                <a href="{{ route('pieces.edit', $piece) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <form action="{{ route('pieces.destroy', $piece) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette pièce ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Colonne latérale --}}
            <div class="col-lg-4">
                {{-- Prix et stock --}}
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h3 class="text-primary mb-3">
                            {{ number_format($piece->prix, 0, ',', ' ') }} FCFA
                        </h3>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Quantité disponible:</span>
                                <strong class="text-{{ $piece->quantite > 10 ? 'success' : ($piece->quantite > 0 ? 'warning' : 'danger') }}">
                                    {{ $piece->quantite }}
                                </strong>
                            </div>
                            @if($piece->quantite > 0)
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-{{ $piece->quantite > 10 ? 'success' : 'warning' }}"
                                         style="width: {{ min(($piece->quantite / 20) * 100, 100) }}%"></div>
                                </div>
                            @endif
                        </div>

                        {{-- Bouton d'ajout au panier pour clients --}}
                        @if(auth()->user()->isClient())
                            @if($piece->disponible && $piece->quantite > 0)
                                <form action="{{ route('panier.add', $piece) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="quantite" class="form-label">Quantité</label>
                                        <input type="number"
                                               name="quantite"
                                               id="quantite"
                                               class="form-control"
                                               value="1"
                                               min="1"
                                               max="{{ $piece->quantite }}">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-cart-plus"></i> Ajouter au panier
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-ban"></i> Indisponible
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Informations vendeur --}}
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Vendeur</h5>

                        @if($piece->user)
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $piece->user->name }}</h6>
                                    <small class="text-muted">
                                        @if($piece->ville)
                                            <i class="fas fa-map-marker-alt"></i> {{ $piece->ville }}
                                        @endif
                                    </small>
                                </div>
                            </div>

                            @if($piece->user->email)
                                <a href="mailto:{{ $piece->user->email }}" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="fas fa-envelope"></i> Contacter
                                </a>
                            @endif
                        @else
                            <div class="alert alert-warning text-center mb-0">
                                <i class="fas fa-user-slash"></i> Ce vendeur a été supprimé.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Localisation --}}
                @if($piece->ville)
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">
                                <i class="fas fa-map-marker-alt text-danger"></i> Localisation
                            </h5>
                            <p class="mb-0">
                                <strong>{{ $piece->ville }}</strong>
                            </p>
                            @if($piece->user?->latitude && $piece->user?->longitude)
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Position GPS disponible
                                </small>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pièces similaires --}}
        @if($piecesSimilaires->count() > 0)
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-th-large"></i> Pièces similaires
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($piecesSimilaires as $similaire)
                            <div class="col-md-3">
                                <div class="card h-100 border">
                                    @if($similaire->photos && count($similaire->photos) > 0)
                                        <img src="{{ asset('storage/' . $similaire->photos[0]) }}"
                                             class="card-img-top"
                                             style="height: 150px; object-fit: cover;"
                                             alt="{{ $similaire->nom }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                            <i class="fas fa-cog fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($similaire->nom, 40) }}</h6>
                                        <div class="mb-2">
                                            @if($similaire->marque)
                                                <span class="badge bg-primary">{{ $similaire->marque->nom }}</span>
                                            @endif
                                            @if($similaire->modele)
                                                <span class="badge bg-secondary">{{ $similaire->modele->nom }}</span>
                                            @endif
                                        </div>
                                        <p class="card-text">
                                            <strong class="text-primary">{{ number_format($similaire->prix, 0, ',', ' ') }} FCFA</strong>
                                        </p>
                                        @if($similaire->ville)
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i> {{ $similaire->ville }}
                                                </small>
                                            </p>
                                        @endif
                                        <a href="{{ route('pieces.show', $similaire) }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-eye"></i> Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Bouton retour --}}
        <div class="mt-4 mb-4">
            <a href="{{ route('pieces.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            padding: 20px;
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.8em;
        }

        code {
            background-color: #f8f9fa;
            padding: 0.2em 0.4em;
            border-radius: 0.25rem;
            color: #e83e8c;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de la quantité max
            const quantiteInput = document.getElementById('quantite');
            if (quantiteInput) {
                quantiteInput.addEventListener('input', function() {
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
            }

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
        });
    </script>
@endpush
