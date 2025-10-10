@extends('layouts.app')

@section('title', 'Commande ' . $commande->numero_commande)

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Commande {{ $commande->numero_commande }}</h1>
            <a href="{{ route('commandes.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>

        @php
            $piecesSupprimees = $commande->items->filter(function($item) {
                return is_null($item->piece);
            });
            $hasPiecesSupprimees = $piecesSupprimees->isNotEmpty();
        @endphp

        @if($hasPiecesSupprimees)
            <div class="alert alert-warning shadow mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Attention : Pièces indisponibles</h5>
                        <p class="mb-0">
                            Cette commande contient <strong>{{ $piecesSupprimees->count() }} pièce(s)</strong> qui ne sont plus disponibles dans le catalogue.
                            Ces articles ont été retirés de la vente par le vendeur.
                            @if(in_array($commande->statut, ['en_attente', 'confirmee']))
                                <br><strong>L'annulation automatique de cette commande n'est plus possible.</strong>
                                Veuillez contacter le service client pour obtenir de l'aide.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

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
                                <th>Statut</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($commande->items as $item)
                                <tr class="{{ is_null($item->piece) ? 'table-warning' : '' }}">
                                    <td>
                                        @if($item->piece)
                                            {{ $item->piece->nom }}
                                        @else
                                            <span class="text-muted fst-italic">
                                                <i class="fas fa-exclamation-circle text-warning"></i>
                                                Pièce supprimée du catalogue
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->piece && $item->piece->casse)
                                            {{ $item->piece->casse->nom_entreprise }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->prix_unitaire, 2, ',', ' ') }} FCFA</td>
                                    <td>{{ $item->quantite }}</td>
                                    <td>{{ number_format($item->prix_unitaire * $item->quantite, 2, ',', ' ') }} FCFA</td>
                                    <td>
                                        @if($item->piece)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Disponible
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-triangle"></i> Indisponible
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td colspan="2"><strong>{{ number_format($commande->total, 2, ',', ' ') }} FCFA</strong></td>
                            </tr>
                            </tfoot>
                        </table>

                        @if($hasPiecesSupprimees)
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-info-circle"></i>
                                <strong>Note :</strong> Le montant total de la commande reste inchangé,
                                même si certaines pièces ne sont plus disponibles.
                                Pour toute question ou demande de remboursement, veuillez contacter notre service client.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header"><h5>Adresse de livraison</h5></div>
                    <div class="card-body">
                        <p id="adresse-livraison-display">{{ $commande->adresse_livraison }}</p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone"></i> Téléphone : <strong>{{ $commande->telephone_livraison }}</strong>
                        </p>

                        @if(auth()->user()->isClient() && $commande->statut === 'en_attente')
                            <hr>
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

                <div class="card shadow mb-4">
                    <div class="card-header"><h5>Informations de paiement</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Mode de paiement :</strong></p>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $commande->mode_paiement)) }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Statut du paiement :</strong></p>
                                <span class="badge bg-{{ $commande->statut_paiement === 'paye' ? 'success' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $commande->statut_paiement)) }}
                                </span>
                            </div>
                        </div>

                        @if($commande->notes)
                            <hr>
                            <p><strong>Notes :</strong></p>
                            <p class="text-muted">{{ $commande->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header"><h5>Statut de la commande</h5></div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="badge bg-{{
                                $commande->statut === 'livree' ? 'success' :
                                ($commande->statut === 'annulee' ? 'danger' :
                                ($commande->statut === 'en_attente' ? 'warning' : 'info'))
                            }} p-3 fs-5">
                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                            </span>
                        </div>

                        <div class="timeline">
                            <div class="timeline-item {{ in_array($commande->statut, ['en_attente','confirmee','en_preparation','expedie','livree']) ? 'active' : '' }}">
                                <i class="fas fa-clock"></i> En attente
                            </div>
                            <div class="timeline-item {{ in_array($commande->statut, ['confirmee','en_preparation','expedie','livree']) ? 'active' : '' }}">
                                <i class="fas fa-check"></i> Confirmée
                            </div>
                            <div class="timeline-item {{ in_array($commande->statut, ['en_preparation','expedie','livree']) ? 'active' : '' }}">
                                <i class="fas fa-box"></i> En préparation
                            </div>
                            <div class="timeline-item {{ in_array($commande->statut, ['expedie','livree']) ? 'active' : '' }}">
                                <i class="fas fa-truck"></i> Expédiée
                            </div>
                            <div class="timeline-item {{ $commande->statut === 'livree' ? 'active' : '' }}">
                                <i class="fas fa-home"></i> Livrée
                            </div>
                        </div>

                        @if($commande->statut === 'annulee')
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-times-circle"></i> Commande annulée
                            </div>
                        @endif

                        @if(auth()->user()->isClient() && in_array($commande->statut, ['en_attente','confirmee']) && !$hasPiecesSupprimees)
                            <hr>
                            <form action="{{ route('commandes.annuler', $commande) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                    <i class="fas fa-times"></i> Annuler la commande
                                </button>
                            </form>
                        @elseif(auth()->user()->isClient() && in_array($commande->statut, ['en_attente','confirmee']) && $hasPiecesSupprimees)
                            <hr>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle"></i>
                                <small>
                                    L'annulation n'est plus possible car certaines pièces ont été retirées du catalogue.
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header"><h5>Informations</h5></div>
                    <div class="card-body">
                        <p><strong>Date de commande :</strong><br>{{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                        <p><strong>Dernière mise à jour :</strong><br>{{ $commande->updated_at->format('d/m/Y à H:i') }}</p>

                        @if(auth()->user()->isCasse())
                            <hr>
                            <p><strong>Client :</strong><br>{{ $commande->user->name }}</p>
                            <p><strong>Email :</strong><br>{{ $commande->user->email }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline-item {
            position: relative;
            padding: 10px 0;
            color: #6c757d;
        }
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -30px;
            top: 50%;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e9ecef;
            border: 2px solid #dee2e6;
        }
        .timeline-item.active {
            color: #198754;
            font-weight: bold;
        }
        .timeline-item.active:before {
            background: #198754;
            border-color: #198754;
        }
        .timeline-item:not(:last-child):after {
            content: '';
            position: absolute;
            left: -24px;
            top: 50%;
            width: 2px;
            height: 100%;
            background: #dee2e6;
        }
        .timeline-item.active:not(:last-child):after {
            background: #198754;
        }
    </style>

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
