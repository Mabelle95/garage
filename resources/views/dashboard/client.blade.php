@extends('layouts.app')

@section('title', 'Tableau de bord - Client')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Tableau de bord</h1>
            <div class="btn-group">
                <a href="{{ route('pieces.index') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Parcourir les pièces
                </a>

            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Commandes totales</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['commandes_total'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Commandes en cours</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['commandes_en_cours'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Articles en panier</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['panier_items'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-basket fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Favoris</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['favoris'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-heart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Commandes récentes -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Mes commandes récentes</h6>
                        <a href="{{ route('commandes') }}" class="btn btn-sm btn-outline-primary">
                            Voir toutes
                        </a>
                    </div>
                    <div class="card-body">
                        @if($recentCommandes && $recentCommandes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recentCommandes as $commande)
                                        <tr>
                                            <td>
                                                <strong>{{ $commande->numero_commande }}</strong>
                                            </td>
                                            <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                                            <td>{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                            <td>
                                            <span class="badge bg-{{ $commande->statut === 'livree' ? 'success' :
                                                                   ($commande->statut === 'annulee' ? 'danger' :
                                                                   ($commande->statut === 'en_attente' ? 'warning' : 'info')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                            </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('commandes.show', $commande) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune commande passée</h5>
                                <p class="text-muted">Vous n'avez pas encore passé de commande</p>
                                <a href="{{ route('pieces.index') }}" class="btn btn-primary">
                                    <i class="fas fa-cog"></i> Parcourir les pièces
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mes demandes d'épaves -->
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Vendre mes épaves</h6>
                        <a href="{{ route('demandes-epaves.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nouvelle épave
                        </a>
                    </div>
                    <div class="card-body">
                        @php
                            $mesDemandes = auth()->user()->demandesEpaves()->latest()->take(3)->get();
                        @endphp

                        @if($mesDemandes->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mesDemandes as $demande)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $demande->marque }} {{ $demande->modele }} ({{ $demande->annee }})</h6>
                                                <p class="mb-1 text-muted small">
                                                    {{ Str::limit($demande->description, 50) }}
                                                </p>
                                                <span class="badge {{ $demande->statut_badge_class }}">{{ ucfirst(str_replace('_', ' ', $demande->statut)) }}</span>
                                                @if($demande->prix_souhaite)
                                                    <span class="badge bg-info">{{ number_format($demande->prix_souhaite, 0, ',', ' ') }} FCFA</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('demandes-epaves.show', $demande) }}" class="btn btn-sm btn-outline-primary">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('demandes-epaves.index') }}" class="btn btn-sm btn-outline-primary">
                                    Voir toutes mes demandes
                                </a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-car-crash fa-3x text-muted mb-2"></i>
                                <p class="text-muted">Aucune demande d'épave</p>
                                <a href="{{ route('demandes-epaves.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Créer une épave
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne de droite -->
            <div class="col-lg-6">
                <!-- Notifications -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Notifications récentes</h6>
                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">
                            Voir toutes
                        </a>
                    </div>
                    <div class="card-body">
                        @if($notifications && $notifications->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($notifications as $notification)
                                    <div class="list-group-item px-0 {{ $notification->lu ? '' : 'bg-light' }}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $notification->titre }}</h6>
                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ $notification->message }}</p>
                                        @if(!$notification->lu)
                                            <small class="text-primary"><i class="fas fa-circle"></i> Non lue</small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-bell fa-3x text-muted mb-2"></i>
                                <h5 class="text-muted">Aucune notification</h5>
                                <p class="text-muted">Vous n'avez aucune notification pour le moment</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pièces populaires -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pièces populaires</h6>
                    </div>
                    <div class="card-body">
                        @if($piecesPopulaires && $piecesPopulaires->count() > 0)
                            <div class="row">
                                @foreach($piecesPopulaires as $piece)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            @if($piece->photos && count($piece->photos) > 0)
                                                <img src="{{ asset('storage/' . $piece->photos[0]) }}"
                                                     class="card-img-top" style="height: 120px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                     style="height: 120px;">
                                                    <i class="fas fa-cog fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="card-body">
                                                <h6 class="card-title">{{ Str::limit($piece->nom, 25) }}</h6>
                                                <p class="card-text small text-muted mb-1">
                                                    {{ $piece->vehicle->marque }} {{ $piece->vehicle->modele }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-primary fw-bold">{{ number_format($piece->prix, 0, ',', ' ') }} FCFA</span>
                                                    <div class="btn-group">
                                                        <a href="{{ route('pieces.show', $piece) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-danger"
                                                                onclick="addToCart({{ $piece->id }})"
                                                                title="Ajouter au panier">
                                                            <i class="fas fa-cart-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('pieces.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-cog"></i> Voir toutes les pièces
                                </a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-cog fa-3x text-muted mb-2"></i>
                                <p class="text-muted">Aucune pièce populaire disponible</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('panier.index') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-shopping-cart me-1"></i> Mon panier
                                    @if($stats['panier_items'] > 0)
                                        <span class="badge bg-danger">{{ $stats['panier_items'] }}</span>
                                    @endif
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('pieces.index') }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-cog me-1"></i> Pièces détachées
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ route('demandes-epaves.create') }}" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-car-crash me-1"></i> Vendre mon épave
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function addToCart(pieceId) {
            let url = '{{ route("panier.add", ["piece" => ":piece"]) }}';
            url = url.replace(':piece', pieceId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantite: 1 })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount();
                        showAlert('Pièce ajoutée au panier avec succès!', 'success');
                    } else {
                        showAlert('Erreur lors de l\'ajout au panier', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Erreur lors de l\'ajout au panier', 'error');
                });
        }

        function updateCartCount() {
            // Implémenter la mise à jour du compteur du panier
            // Cela peut être fait via une requête AJAX ou en rechargeant la page
            window.location.reload();
        }

        function showAlert(message, type) {
            // Créer une alerte Bootstrap
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

            document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.container-fluid').firstChild);

            // Auto-dismiss après 3 secondes
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts après 5 secondes
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    <style>
        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .stats-card {
            border-left: 4px solid;
        }

        .border-left-primary { border-left-color: #4e73df; }
        .border-left-success { border-left-color: #1cc88a; }
        .border-left-info { border-left-color: #36b9cc; }
        .border-left-warning { border-left-color: #f6c23e; }

        .shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        }

        .list-group-item {
            border: 1px solid #e3e6f0;
            margin-bottom: -1px;
        }

        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endsection
