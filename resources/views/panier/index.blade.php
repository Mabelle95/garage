@extends('layouts.app')

@section('title', 'Mon panier')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Mon panier</h1>
            @if($panier->items->count() > 0)
                <div>
                    <span class="h4 text-primary me-3">Total: {{ number_format($panier->getTotal(), 2, ',', ' ') }} FCFA</span>
                    <a href="{{ route('commandes.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-credit-card"></i> Passer la commande
                    </a>
                </div>
            @endif
        </div>

        @if($panier->items->count() > 0)
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Pièce</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($panier->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->piece && $item->piece->photos && count($item->piece->photos) > 0)
                                                <img src="{{ asset('storage/' . $item->piece->photos[0]) }}"
                                                     class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-cog text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->piece->nom ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">
                                                    @if($item->piece && $item->piece->vehicle)
                                                        {{ $item->piece->vehicle->marque ?? '' }} {{ $item->piece->vehicle->modele ?? '' }} •
                                                        {{ $item->piece->vehicle->casse->nom_entreprise ?? '' }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->piece->prix ?? 0, 2, ',', ' ') }} FCFA</td>
                                    <td>
                                        <form action="{{ route('panier.update', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group" style="width: 120px;">
                                                <input type="number" name="quantite" class="form-control"
                                                       value="{{ $item->quantite ?? 1 }}" min="1" max="{{ $item->piece->quantite ?? 999 }}">
                                                <button type="submit" class="btn btn-outline-primary">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>{{ number_format(($item->quantite ?? 0) * ($item->piece->prix ?? 0), 2, ',', ' ') }} FCFA</td>
                                    <td>
                                        <form action="{{ route('panier.remove', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <form action="{{ route('panier.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('Vider tout le panier ?')">
                                <i class="fas fa-trash"></i> Vider le panier
                            </button>
                        </form>

                        <div class="text-end">
                            <h4>Sous-total: {{ number_format($panier->getTotal(), 2, ',', ' ') }} FCFA</h4>
                            <small class="text-muted">Frais de livraison calculés à l'étape suivante</small><br>
                            <a href="{{ route('commandes.create') }}" class="btn btn-primary btn-lg mt-2">
                                <i class="fas fa-credit-card"></i> Commander maintenant
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4>Votre panier est vide</h4>
                <p class="text-muted">Ajoutez des pièces détachées à votre panier</p>
                <a href="{{ route('pieces.index') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Parcourir les pièces
                </a>
            </div>
        @endif
    </div>
@endsection
