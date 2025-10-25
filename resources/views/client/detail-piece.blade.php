@extends('layouts.client')

@section('title', $piece->nom)

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ $piece->nom }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('client.recherche-pieces') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- Galerie photos -->
            <div class="card mb-4">
                <div class="card-body">
                    @if($piece->photos && count($piece->photos) > 0)
                        <div id="carouselPiece" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($piece->photos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $photo) }}"
                                             class="d-block w-100 rounded"
                                             style="height: 400px; object-fit: contain; background-color: #f8f9fa;">
                                    </div>
                                @endforeach
                            </div>
                            @if(count($piece->photos) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselPiece" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselPiece" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>

                        <!-- Miniatures -->
                        @if(count($piece->photos) > 1)
                            <div class="row mt-3">
                                @foreach($piece->photos as $index => $photo)
                                    <div class="col-3">
                                        <img src="{{ asset('storage/' . $photo) }}"
                                             class="img-thumbnail cursor-pointer"
                                             style="height: 80px; object-fit: cover;"
                                             onclick="$('#carouselPiece').carousel({{ $index }})">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-cog fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Aucune photo disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Informations principales -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="text-primary">{{ number_format($piece->prix, 2) }}FCFA</h3>
                        <span class="badge bg-{{ $piece->quantite > 0 ? 'success' : 'danger' }} fs-6">
                        {{ $piece->quantite > 0 ? 'En stock' : 'Rupture' }}
                    </span>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-info">{{ $piece->etat }}</span>
                        <span class="badge bg-secondary">{{ $piece->categorie }}</span>
                    </div>

                    <div class="mb-4">
                        <h5>Informations véhicule</h5>
                        <p class="mb-1">
                            <i class="fas fa-car text-muted me-2"></i>
                            {{ $piece->vehicule->marque }} {{ $piece->vehicule->modele }} ({{ $piece->vehicule->annee }})
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-tag text-muted me-2"></i>
                            Référence: {{ $piece->reference }}
                        </p>
                    </div>

                    @if($piece->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $piece->description }}</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        @if($piece->quantite > 0)
                            <div class="input-group mb-2">
                                <span class="input-group-text">Quantité</span>
                                <input type="number" id="quantite" class="form-control" value="1" min="1" max="{{ $piece->quantite }}">
                                <button class="btn btn-primary ajouter-panier" data-piece-id="{{ $piece->id }}">
                                    <i class="fas fa-cart-plus"></i> Ajouter au panier
                                </button>
                            </div>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="fas fa-times"></i> Rupture de stock
                            </button>
                        @endif

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-danger flex-fill toggle-favori {{ $estDansFavoris ? 'active text-danger' : '' }}"
                                    data-piece-id="{{ $piece->id }}">
                                <i class="fas fa-heart"></i>
                                {{ $estDansFavoris ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                            </button>

                            <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalContacter">
                                <i class="fas fa-envelope"></i> Contacter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations vendeur -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations du vendeur</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-store fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="mb-0">{{ $piece->casse->name }}</h6>
                            <small class="text-muted">Casse automobile</small>
                        </div>
                    </div>

                    @if($piece->casse->adresse)
                        <p class="mb-1">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            {{ $piece->casse->adresse }}
                        </p>
                    @endif

                    @if($piece->casse->telephone)
                        <p class="mb-1">
                            <i class="fas fa-phone text-muted me-2"></i>
                            {{ $piece->casse->telephone }}
                        </p>
                    @endif

                    <div class="mt-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Voir autres pièces
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-directions"></i> Itinéraire
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pièces similaires -->
    @if($piecesSimilaires->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-3">Pièces similaires</h4>
                <div class="row">
                    @foreach($piecesSimilaires as $pieceSimilaire)
                        <div class="col-md-3 mb-4">
                            <div class="card card-hover h-100">
                                @if($pieceSimilaire->photos && count($pieceSimilaire->photos) > 0)
                                    <img src="{{ asset('storage/' . $pieceSimilaire->photos[0]) }}"
                                         class="card-img-top" alt="{{ $pieceSimilaire->nom }}"
                                         style="height: 150px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 150px;">
                                        <i class="fas fa-cog fa-2x text-muted"></i>
                                    </div>
                                @endif

                                <div class="card-body">
                                    <h6 class="card-title">{{ Str::limit($pieceSimilaire->nom, 30) }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            {{ number_format($pieceSimilaire->prix, 2) }} FCFA<br>
                                            {{ $pieceSimilaire->vehicule->marque }} {{ $pieceSimilaire->vehicule->modele }}
                                        </small>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('client.detail-piece', $pieceSimilaire) }}"
                                       class="btn btn-sm btn-outline-primary w-100">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Modal contact -->
    <div class="modal fade" id="modalContacter" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contacter le vendeur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Sujet</label>
                            <input type="text" class="form-control" value="Demande d'information: {{ $piece->nom }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="4"
                                      placeholder="Posez votre question au vendeur..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary">Envoyer le message</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .cursor-pointer { cursor: pointer; }
        .carousel-item img { background-color: #f8f9fa; }
    </style>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ajouter au panier
                document.querySelector('.ajouter-panier').addEventListener('click', function() {
                    const quantite = document.getElementById('quantite').value;
                    const pieceId = this.dataset.pieceId;

                    fetch(`/client/panier/ajouter/${pieceId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ quantite: parseInt(quantite) })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Pièce ajoutée au panier !');
                            }
                        });
                });

                // Gestion favoris
                document.querySelector('.toggle-favori').addEventListener('click', function() {
                    const pieceId = this.dataset.pieceId;
                    const isFavori = this.classList.contains('active');

                    const url = isFavori ?
                        `/client/favoris/retirer/${pieceId}` :
                        `/client/favoris/ajouter/${pieceId}`;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.classList.toggle('active');
                                this.classList.toggle('text-danger');
                                this.innerHTML = isFavori ?
                                    '<i class="fas fa-heart"></i> Ajouter aux favoris' :
                                    '<i class="fas fa-heart"></i> Retirer des favoris';
                            }
                        });
                });
            });
        </script>
    @endsection
