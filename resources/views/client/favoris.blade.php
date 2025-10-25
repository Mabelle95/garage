@extends('layouts.client')

@section('title', 'Mes Favoris')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mes Favoris</h1>
        <span class="badge bg-primary">{{ $favoris->total() }} pièce(s)</span>
    </div>

    @if($favoris->count() > 0)
        <div class="row">
            @foreach($favoris as $favori)
                @php $piece = $favori->piece; @endphp
                <div class="col-md-4 mb-4">
                    <div class="card card-hover h-100">
                        @if($piece->photos && count($piece->photos) > 0)
                            <img src="{{ asset('storage/' . $piece->photos[0]) }}"
                                 class="card-img-top" alt="{{ $piece->nom }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="fas fa-cog fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $piece->nom }}</h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    {{ $piece->vehicule->marque }} {{ $piece->vehicule->modele }}
                                    ({{ $piece->vehicule->annee }})<br>
                                    État: {{ $piece->etat }}<br>
                                    Vendeur: {{ $piece->casse->name }}
                                </small>
                            </p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="h5 text-primary">{{ number_format($piece->prix, 2) }}FCFA</span>
                                    <span class="badge bg-{{ $piece->quantite > 0 ? 'success' : 'danger' }}">
                                    {{ $piece->quantite }} en stock
                                </span>
                                </div>

                                <div class="d-flex gap-2">
                                    @if($piece->quantite > 0)
                                        <button class="btn btn-primary btn-sm ajouter-panier"
                                                data-piece-id="{{ $piece->id }}">
                                            <i class="fas fa-cart-plus"></i> Panier
                                        </button>
                                    @endif
                                    <button class="btn btn-danger btn-sm toggle-favori active"
                                            data-piece-id="{{ $piece->id }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <a href="{{ route('client.detail-piece', $piece) }}"
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $favoris->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-heart fa-3x text-muted mb-3"></i>
            <h4>Aucun favori</h4>
            <p class="text-muted">Ajoutez des pièces à vos favoris pour les retrouver facilement</p>
            <a href="{{ route('client.recherche-pieces') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher des pièces
            </a>
        </div>
    @endif
@endsection
