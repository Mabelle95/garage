@extends('layouts.client')

@section('title', 'Recherche de Pièces')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Recherche de Pièces Détachées</h1>
    </div>

    <!-- Filtres de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('client.recherche-pieces') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Marque</label>
                        <select name="marque" class="form-select">
                            <option value="">Toutes les marques</option>
                            @foreach($marques as $marque)
                                <option value="{{ $marque }}" {{ request('marque') == $marque ? 'selected' : '' }}>
                                    {{ $marque }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Modèle</label>
                        <input type="text" name="modele" class="form-control" value="{{ request('modele') }}" placeholder="Modèle">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Année min</label>
                        <input type="number" name="annee_min" class="form-control" value="{{ request('annee_min') }}" placeholder="2000">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Année max</label>
                        <input type="number" name="annee_max" class="form-control" value="{{ request('annee_max') }}" placeholder="2024">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Catégorie</label>
                        <select name="categorie" class="form-select">
                            <option value="">Toutes</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie }}" {{ request('categorie') == $categorie ? 'selected' : '' }}>
                                    {{ $categorie }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Prix min</label>
                        <input type="number" name="prix_min" class="form-control" value="{{ request('prix_min') }}" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Prix max</label>
                        <input type="number" name="prix_max" class="form-control" value="{{ request('prix_max') }}" placeholder="1000">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">État</label>
                        <select name="etat" class="form-select">
                            <option value="">Tous</option>
                            <option value="neuf" {{ request('etat') == 'neuf' ? 'selected' : '' }}>Neuf</option>
                            <option value="occasion" {{ request('etat') == 'occasion' ? 'selected' : '' }}>Occasion</option>
                            <option value="reconditionne" {{ request('etat') == 'reconditionne' ? 'selected' : '' }}>Reconditionné</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tri par</label>
                        <select name="sort" class="form-select">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date</option>
                            <option value="prix" {{ request('sort') == 'prix' ? 'selected' : '' }}>Prix</option>
                            <option value="nom" {{ request('sort') == 'nom' ? 'selected' : '' }}>Nom</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                        <a href="{{ route('client.recherche-pieces') }}" class="btn btn-secondary">Réinitialiser</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    <div class="row">
        @foreach($pieces as $piece)
            <div class="col-md-4 mb-4">
                <div class="card card-hover h-100">
                    @if($piece->photos && count($piece->photos) > 0)
                        <img src="{{ asset('storage/' . $piece->photos[0]) }}" class="card-img-top" alt="{{ $piece->nom }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-cog fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $piece->nom }}</h5>
                        <p class="card-text">
                            <small class="text-muted">
                                {{ $piece->vehicule->marque }} {{ $piece->vehicule->modele }} ({{ $piece->vehicule->annee }})<br>
                                État: {{ $piece->etat }}<br>
                                Catégorie: {{ $piece->categorie }}
                            </small>
                        </p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary">{{ number_format($piece->prix, 2) }} FCFA</span>
                                <span class="badge bg-{{ $piece->quantite > 0 ? 'success' : 'danger' }}">
                                {{ $piece->quantite }} en stock
                            </span>
                            </div>

                            <div class="d-flex gap-2 mt-2">
                                @if($piece->quantite > 0)
                                    <button class="btn btn-primary btn-sm ajouter-panier" data-piece-id="{{ $piece->id }}">
                                        <i class="fas fa-cart-plus"></i> Panier
                                    </button>
                                @endif
                                <button class="btn btn-outline-danger btn-sm toggle-favori {{ auth()->user()->favoris()->where('piece_id', $piece->id)->exists() ? 'active text-danger' : '' }}"
                                        data-piece-id="{{ $piece->id }}">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <a href="{{ route('client.detail-piece', $piece) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $pieces->links() }}
    </div>

    @if($pieces->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4>Aucune pièce trouvée</h4>
            <p class="text-muted">Essayez de modifier vos critères de recherche</p>
        </div>
    @endif
@endsection
