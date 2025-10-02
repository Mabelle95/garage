@extends('layouts.app')

@section('title', 'Passer la commande')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Passer la commande</h1>
        <a href="{{ route('panier.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au panier
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header"><h5 class="m-0">Récapitulatif de la commande</h5></div>
                <div class="card-body">
                    @if($panier && $panier->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Pièce</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($panier->items as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->piece->nom }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $item->piece->vehicle->marque }} {{ $item->piece->vehicle->modele }}
                                                    • {{ $item->piece->vehicle->casse->nom_entreprise }}
                                                </small>
                                            </td>
                                            <td>{{ number_format($item->piece->prix, 2, ',', ' ') }}FCFA</td>
                                            <td>{{ $item->quantite }}</td>
                                            <td>{{ number_format($item->quantite * $item->piece->prix, 2, ',', ' ') }}FCFA</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>{{ number_format($panier->getTotal(), 2, ',', ' ') }}FCFA</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">Votre panier est vide.</div>
                    @endif
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header"><h5 class="m-0">Informations de livraison</h5></div>
                <div class="card-body">
                    <form action="{{ route('commandes.store') }}" method="POST" id="commandeForm">
                        @csrf

                        <div class="mb-3">
                            <label for="adresse_livraison" class="form-label">Adresse de livraison *</label>
                            <textarea class="form-control" id="adresse_livraison" name="adresse_livraison" rows="3" required>{{ old('adresse_livraison', auth()->user()->adresse) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="telephone_livraison" class="form-label">Téléphone de livraison *</label>
                            <input type="text" class="form-control" id="telephone_livraison" name="telephone_livraison" required value="{{ old('telephone_livraison', auth()->user()->telephone) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ajouter ma position actuelle</label>
                            <div>
                                <button type="button" class="btn btn-outline-primary" onclick="openGeoPicker()">
                                    <i class="fas fa-map-marker-alt"></i> Choisir sur la carte
                                </button>
                            </div>
                        </div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div class="mb-3">
                            <label for="mode_paiement" class="form-label">Mode de paiement *</label>
                            <select class="form-select" id="mode_paiement" name="mode_paiement" required>
                                <option value="carte_bancaire">Carte bancaire</option>
                                <option value="paypal">PayPal</option>
                                <option value="virement">Virement bancaire</option>
                                <option value="especes">Espèces (à la livraison)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg" {{ !$panier || $panier->items->count() === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-credit-card"></i> Confirmer la commande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openGeoPicker() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude;
            const lon = pos.coords.longitude;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lon;

            let adresse = document.getElementById('adresse_livraison');
            adresse.value = `${lat}, ${lon}`
            console.log(adresse);
            alert(`Votre position a été ajoutée.\nLatitude: ${lat}\nLongitude: ${lon}`);
        }, err => {
            alert('Impossible de récupérer votre position: ' + err.message);
        });
    } else {
        alert("Géolocalisation non supportée par votre navigateur.");
    }
}
</script>
@endsection
