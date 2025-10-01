@extends('layouts.client')

@section('title', 'Mon Panier')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mon Panier</h1>
    </div>

    @if($panierItems->count() > 0)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Articles dans le panier</h5>
                    </div>
                    <div class="card-body">
                        @foreach($panierItems as $item)
                            <div class="row align-items-center mb-3 pb-3 border-bottom">
                                <div class="col-md-2">
                                    @if($item->piece->photos && count($item->piece->photos) > 0)
                                        <img src="{{ asset('storage/' . $item->piece->photos[0]) }}"
                                             class="img-fluid rounded" alt="{{ $item->piece->nom }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                            <i class="fas fa-cog fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6>{{ $item->piece->nom }}</h6>
                                    <small class="text-muted">
                                        {{ $item->piece->vehicule->marque }} {{ $item->piece->vehicule->modele }}<br>
                                        Vendeur: {{ $item->piece->casse->name }}
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <span class="h6">{{ number_format($item->piece->prix, 2) }} FCFA</span>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control form-control-sm"
                                           value="{{ $item->quantite }}" min="1" max="{{ $item->piece->quantite }}"
                                           onchange="updateQuantite({{ $item->id }}, this.value)">
                                </div>
                                <div class="col-md-2">
                                    <span class="h6">{{ number_format($item->quantite * $item->piece->prix, 2) }}FCFA</span>
                                    <button class="btn btn-sm btn-outline-danger mt-1"
                                            onclick="retirerDuPanier({{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Récapitulatif</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total:</span>
                            <span>{{ number_format($total, 2) }}FCFA</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison:</span>
                            <span>Gratuite</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>{{ number_format($total, 2) }} FCFA</strong>
                        </div>

                        <form action="{{ route('client.passer-commande') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Adresse de livraison</label>
                                <textarea name="adresse_livraison" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Méthode de paiement</label>
                                <select name="methode_paiement" class="form-select" required>
                                    <option value="carte">Carte bancaire</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="virement">Virement bancaire</option>
                                    <option value="especes">Espèces à la livraison</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-credit-card"></i> Passer la commande
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4>Votre panier est vide</h4>
            <p class="text-muted">Ajoutez des pièces à votre panier pour commencer vos achats</p>
            <a href="{{ route('client.recherche-pieces') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher des pièces
            </a>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        function updateQuantite(panierId, quantite) {
            fetch(`/client/panier/update/${panierId}`, {
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
                        location.reload();
                    }
                });
        }

        function retirerDuPanier(panierId) {
            if (confirm('Êtes-vous sûr de vouloir retirer cet article du panier ?')) {
                fetch(`/client/panier/retirer/${panierId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => {
                        if (response.ok) {
                            location.reload();
                        }
                    });
            }
        }
    </script>
@endsection
