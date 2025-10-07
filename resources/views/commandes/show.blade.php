@extends('layouts.app')

@section('title', 'Commande ' . $commande->numero_commande)

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Commande {{ $commande->numero_commande }}</h1>
            <a href="{{ route('commandes.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header"><h5>Détails de la commande</h5></div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Pièce</th>
                                <th>Casse</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($commande->items as $item)
                                <tr>
                                    <td>{{ $item->piece->nom }}</td>
                                    {{-- Utiliser directement la relation casse depuis la pièce --}}
                                    <td>{{ $item->piece->casse->nom_entreprise ?? 'N/A' }}</td>
                                    <td>{{ number_format($item->prix_unitaire, 2, ',', ' ') }} FCFA</td>
                                    <td>{{ $item->quantite }}</td>
                                    <td>{{ number_format($item->prix_unitaire * $item->quantite, 2, ',', ' ') }} FCFA</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td><strong>{{ number_format($commande->total, 2, ',', ' ') }} FCFA</strong></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header"><h5>Adresse de livraison</h5></div>
                    <div class="card-body">
                        <p id="adresse-livraison-display">{{ $commande->adresse_livraison }}</p>
                        @if(auth()->user()->isClient() && $commande->statut === 'en_attente')
                            <button type="button" class="btn btn-outline-primary" onclick="openGeoPicker()">
                                <i class="fas fa-map-marker-alt"></i> Ajouter ma position actuelle
                            </button>

                            <form id="geoForm" action="{{ route('commandes.update-adresse', $commande) }}" method="POST" class="mt-2 d-none">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <button type="submit" class="btn btn-primary">Confirmer la localisation</button>
                            </form>
                        @endif
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

                    document.getElementById('geoForm').classList.remove('d-none');
                    document.getElementById('adresse-livraison-display').textContent = `Lat: ${lat}, Lon: ${lon}`;
                }, err => {
                    alert('Impossible de récupérer votre position: ' + err.message);
                });
            } else {
                alert("Géolocalisation non supportée par votre navigateur.");
            }
        }
    </script>
@endsection
