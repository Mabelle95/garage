@extends('layouts.client')

@section('title', 'Détail Commande #' . $commande->numero_commande)

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Commande #{{ $commande->numero_commande }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('client.commandes') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux commandes
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Informations de la commande -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informations de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date de commande:</strong><br>
                                {{ $commande->created_at->format('d/m/Y à H:i') }}</p>

                            <p><strong>Statut:</strong><br>
                                <span class="badge bg-{{ [
                            'en_attente' => 'warning',
                            'confirmee' => 'info',
                            'preparation' => 'primary',
                            'expediee' => 'secondary',
                            'livree' => 'success',
                            'annulee' => 'danger'
                        ][$commande->statut] ?? 'secondary' }}">
                            {{ str_replace('_', ' ', $commande->statut) }}
                        </span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Méthode de paiement:</strong><br>
                                {{ ucfirst($commande->methode_paiement) }}</p>

                            <p><strong>Statut paiement:</strong><br>
                                <span class="badge bg-{{ [
                            'en_attente' => 'warning',
                            'paye' => 'success',
                            'echec' => 'danger',
                            'rembourse' => 'info'
                        ][$commande->statut_paiement] ?? 'secondary' }}">
                            {{ $commande->statut_paiement }}
                        </span></p>
                        </div>
                    </div>

                    <p><strong>Adresse de livraison:</strong><br>
                        {{ $commande->adresse_livraison }}</p>

                    @if($commande->date_livraison_estimee)
                        <p><strong>Livraison estimée:</strong><br>
                            {{ $commande->date_livraison_estimee->format('d/m/Y') }}</p>
                    @endif

                    @if($commande->notes)
                        <p><strong>Notes:</strong><br>
                            {{ $commande->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Articles de la commande -->
            <div class="card">
                <div class="card-header">
                    <h5>Articles commandés</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Pièce</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($commande->pieces as $piece)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($piece->photos && count($piece->photos) > 0)
                                                <img src="{{ asset('storage/' . $piece->photos[0]) }}"
                                                     class="rounded me-3" width="50" height="50"
                                                     style="object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $piece->nom }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $piece->vehicule->marque }} {{ $piece->vehicule->modele }}
                                                    • {{ $piece->etat }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($piece->pivot->prix_unitaire, 2) }} FCFA</td>
                                    <td>{{ $piece->pivot->quantite }}</td>
                                    <td>{{ number_format($piece->pivot->prix_unitaire * $piece->pivot->quantite, 2) }} FCFA</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>{{ number_format($commande->total, 2) }}FCFA</strong></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informations de la casse -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informations du vendeur</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $commande->casse->name }}</h6>
                    @if($commande->casse->adresse)
                        <p class="mb-1">
                            <i class="fas fa-map-marker-alt text-muted"></i>
                            {{ $commande->casse->adresse }}
                        </p>
                    @endif
                    @if($commande->casse->telephone)
                        <p class="mb-1">
                            <i class="fas fa-phone text-muted"></i>
                            {{ $commande->casse->telephone }}
                        </p>
                    @endif
                    @if($commande->casse->email)
                        <p class="mb-1">
                            <i class="fas fa-envelope text-muted"></i>
                            {{ $commande->casse->email }}
                        </p>
                    @endif

                    <hr>
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-comment"></i> Contacter le vendeur
                    </a>
                </div>
            </div>

            <!-- Suivi de commande -->
            <div class="card">
                <div class="card-header">
                    <h5>Suivi de commande</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $etapes = [
                                'en_attente' => ['icon' => 'clock', 'text' => 'Commande en attente', 'active' => true],
                                'confirmee' => ['icon' => 'check', 'text' => 'Commande confirmée', 'active' => in_array($commande->statut, ['confirmee', 'preparation', 'expediee', 'livree'])],
                                'preparation' => ['icon' => 'cog', 'text' => 'En préparation', 'active' => in_array($commande->statut, ['preparation', 'expediee', 'livree'])],
                                'expediee' => ['icon' => 'shipping-fast', 'text' => 'Expédiée', 'active' => in_array($commande->statut, ['expediee', 'livree'])],
                                'livree' => ['icon' => 'check-circle', 'text' => 'Livrée', 'active' => $commande->statut === 'livree']
                            ];
                        @endphp

                        @foreach($etapes as $etape => $data)
                            <div class="timeline-item {{ $data['active'] ? 'active' : '' }}">
                                <div class="timeline-icon">
                                    <i class="fas fa-{{ $data['icon'] }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <p class="mb-0">{{ $data['text'] }}</p>
                                    @if($data['active'] && $commande->statut === $etape)
                                        <small class="text-muted">En cours</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
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
            margin-bottom: 20px;
        }
        .timeline-item.active .timeline-icon {
            background-color: #0d6efd;
            color: white;
        }
        .timeline-icon {
            position: absolute;
            left: -30px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .timeline-content {
            padding-left: 10px;
        }
    </style>
@endsection
