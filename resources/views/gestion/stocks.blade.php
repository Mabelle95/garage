@extends('layouts.app')

@section('title', 'Gestion des stocks')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-warehouse me-2"></i>Gestion des stocks</h1>
            <div class="btn-group">
                <a href="{{ route('pieces.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Ajouter une pièce
                </a>
                <a href="{{ route('demandes-epaves.create') }}" class="btn btn-success">
                    <i class="fas fa-car-crash me-1"></i> Ajouter un véhicule/épave
                </a>
            </div>
        </div>

        <!-- Navigation par onglets -->
        <ul class="nav nav-tabs mb-4" id="stockTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pieces-tab" data-bs-toggle="tab" data-bs-target="#pieces" type="button" role="tab">
                    <i class="fas fa-cog me-2"></i>Pièces détachées
                    <span class="badge bg-primary ms-2">{{ $totalPieces }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="epaves-tab" data-bs-toggle="tab" data-bs-target="#epaves" type="button" role="tab">
                    <i class="fas fa-car-crash me-2"></i>Véhicules & Épaves
                    <span class="badge bg-success ms-2">{{ $mesDemandes->total() }}</span>
                </button>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content" id="stockTabsContent">

            <!-- ========================================= -->
            <!-- ONGLET 1 : PIÈCES DÉTACHÉES -->
            <!-- ========================================= -->
            <div class="tab-pane fade show active" id="pieces" role="tabpanel">

                <!-- Statistiques globales des pièces -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-cog fa-2x text-success mb-2"></i>
                                <h3>{{ $totalPieces }}</h3>
                                <p class="text-muted mb-0">Pièces différentes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-boxes fa-2x text-info mb-2"></i>
                                <h3>{{ $totalStock }}</h3>
                                <p class="text-muted mb-0">Stock total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-warning mb-2"></i>
                                <h3>{{ $piecesDisponibles }}</h3>
                                <p class="text-muted mb-0">Disponibles</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                <h3>{{ $stockFaible->count() + $stockVide->count() }}</h3>
                                <p class="text-muted mb-0">Alertes stock</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des pièces -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Stock de Pièces</h5>
                    </div>
                    <div class="card-body">
                        @if($pieces->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Pièce</th>
                                        <th>État</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Disponible</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pieces as $piece)
                                        <tr>
                                            <td>
                                                <strong>{{ $piece->nom }}</strong>
                                                @if($piece->reference_constructeur)
                                                    <br><small class="text-muted">Réf: {{ $piece->reference_constructeur }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $piece->etat)) }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($piece->prix, 2, ',', ' ') }} FCFA</td>
                                            <td>
                                                <span class="badge bg-{{ $piece->quantite > 10 ? 'success' : ($piece->quantite > 0 ? 'warning' : 'danger') }}">
                                                    {{ $piece->quantite }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $piece->disponible ? 'success' : 'danger' }}">
                                                    {{ $piece->disponible ? 'Oui' : 'Non' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('pieces.show', $piece) }}" class="btn btn-outline-primary" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pieces.edit', $piece) }}" class="btn btn-outline-secondary" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-cog fa-4x text-muted mb-3"></i>
                                <h4>Aucune pièce en stock</h4>
                                <p class="text-muted">Commencez par ajouter vos pièces détachées</p>
                                <a href="{{ route('pieces.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter une pièce
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alertes de stock faible -->
                @if($stockFaible->count() > 0 || $stockVide->count() > 0)
                    <div class="row mt-4">
                        @if($stockFaible->count() > 0)
                            <div class="col-md-6">
                                <div class="card shadow border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Stock faible ({{ $stockFaible->count() }} pièce(s))</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @foreach($stockFaible as $piece)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>{{ $piece->nom }}</span>
                                                    <span class="badge bg-warning">{{ $piece->quantite }} restant(s)</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($stockVide->count() > 0)
                            <div class="col-md-6">
                                <div class="card shadow border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h6 class="mb-0"><i class="fas fa-times-circle me-2"></i>Stock épuisé ({{ $stockVide->count() }} pièce(s))</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @foreach($stockVide as $piece)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>{{ $piece->nom }}</span>
                                                    <a href="{{ route('pieces.edit', $piece) }}" class="btn btn-sm btn-outline-primary">
                                                        Réapprovisionner
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- ========================================= -->
            <!-- ONGLET 2 : VÉHICULES & ÉPAVES -->
            <!-- ========================================= -->
            <div class="tab-pane fade" id="epaves" role="tabpanel">

                <!-- Statistiques des demandes d'épaves -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-car fa-2x text-primary mb-2"></i>
                                <h3>{{ $statsEpaves['vehicules'] }}</h3>
                                <p class="text-muted mb-0">Véhicules</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-car-crash fa-2x text-danger mb-2"></i>
                                <h3>{{ $statsEpaves['epaves'] }}</h3>
                                <p class="text-muted mb-0">Épaves</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h3>{{ $statsEpaves['en_attente'] }}</h3>
                                <p class="text-muted mb-0">En attente</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h3>{{ $statsEpaves['vendus'] }}</h3>
                                <p class="text-muted mb-0">Vendus</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des demandes d'épaves -->
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-car-crash me-2"></i>Mes Véhicules & Épaves</h5>
                    </div>
                    <div class="card-body">
                        @if($mesDemandes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Véhicule</th>
                                        <th>Année</th>
                                        <th>État</th>
                                        <th>Prix souhaité</th>
                                        <th>Offres</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($mesDemandes as $demande)
                                        <tr>
                                            <td>
                                                <span class="badge {{ $demande->type_badge_class }}">
                                                    <i class="fas fa-{{ $demande->type == 'vehicule' ? 'car' : 'car-crash' }} me-1"></i>
                                                    {{ $demande->type_libelle }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $demande->marque }} {{ $demande->modele }}</strong>
                                                @if($demande->numero_chassis)
                                                    <br><small class="text-muted">Châssis: {{ $demande->numero_chassis }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $demande->annee }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst($demande->etat) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($demande->prix_souhaite)
                                                    {{ number_format($demande->prix_souhaite, 0, ',', ' ') }} FCFA
                                                @else
                                                    <span class="text-muted">À négocier</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($demande->offres->count() > 0)
                                                    <span class="badge bg-info">
                                                        {{ $demande->offres->count() }} offre(s)
                                                    </span>
                                                    @php
                                                        $meilleureOffre = $demande->getMeilleureOffre();
                                                    @endphp
                                                    @if($meilleureOffre)
                                                        <br><small class="text-success">
                                                            Meilleure: {{ number_format($meilleureOffre->prix_offert, 0, ',', ' ') }} FCFA
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Aucune</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $demande->statut_badge_class }}">
                                                    {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('demandes-epaves.show', $demande) }}"
                                                       class="btn btn-outline-primary"
                                                       title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($demande->statut == 'en_attente')
                                                        <a href="{{ route('demandes-epaves.edit', $demande) }}"
                                                           class="btn btn-outline-secondary"
                                                           title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    {{-- Bouton de messagerie pour statut vendu ou accepté --}}
                                                    @if(in_array($demande->statut, ['vendu', 'accepte']))
                                                        @php
                                                            $offreAcceptee = $demande->offres->where('statut', 'accepte')->first();
                                                        @endphp
                                                        @if($offreAcceptee && $offreAcceptee->user)
                                                            <a href="{{ route('messages.conversation', ['userId' => $offreAcceptee->user->id]) }}"
                                                               class="btn btn-outline-success"
                                                               title="Contacter l'acheteur">
                                                                <i class="fas fa-envelope"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $mesDemandes->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-car-crash fa-4x text-muted mb-3"></i>
                                <h4>Aucun véhicule ou épave</h4>
                                <p class="text-muted">Commencez par ajouter vos véhicules ou épaves à vendre</p>
                                <a href="{{ route('demandes-epaves.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Ajouter un véhicule/épave
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alertes pour les demandes -->
                @if($mesDemandes->where('statut', 'en_attente')->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card shadow border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Demandes en attente d'offres ({{ $mesDemandes->where('statut', 'en_attente')->count() }})
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($mesDemandes->where('statut', 'en_attente') as $demande)
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                                    <div>
                                                        <strong>{{ $demande->marque }} {{ $demande->modele }}</strong> ({{ $demande->annee }})
                                                        <br>
                                                        <small class="text-muted">
                                                            @if($demande->offres->count() > 0)
                                                                <i class="fas fa-tag text-success"></i> {{ $demande->offres->count() }} offre(s) reçue(s)
                                                            @else
                                                                <i class="fas fa-clock text-warning"></i> En attente d'offres
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <a href="{{ route('demandes-epaves.show', $demande) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> Voir
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Section des demandes vendues/acceptées avec messagerie -->
                @if($mesDemandes->whereIn('statut', ['vendu', 'accepte'])->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card shadow border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Ventes conclues ({{ $mesDemandes->whereIn('statut', ['vendu', 'accepte'])->count() }})
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($mesDemandes->whereIn('statut', ['vendu', 'accepte']) as $demande)
                                            @php
                                                $offreAcceptee = $demande->offres->where('statut', 'accepte')->first();
                                            @endphp
                                            <div class="col-md-6 mb-3">
                                                <div class="p-3 border rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <strong>{{ $demande->marque }} {{ $demande->modele }}</strong> ({{ $demande->annee }})
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-{{ $demande->type == 'vehicule' ? 'car' : 'car-crash' }}"></i>
                                                                {{ $demande->type_libelle }}
                                                            </small>
                                                        </div>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check"></i> {{ ucfirst($demande->statut) }}
                                                        </span>
                                                    </div>

                                                    @if($offreAcceptee)
                                                        <div class="mb-2 p-2 bg-white rounded border">
                                                            <small class="text-muted">Acheteur :</small>
                                                            <strong class="d-block">{{ $offreAcceptee->user->name }}</strong>
                                                            <small class="text-success">
                                                                <i class="fas fa-coins"></i>
                                                                Prix : {{ number_format($offreAcceptee->prix_offert, 0, ',', ' ') }} FCFA
                                                            </small>
                                                        </div>
                                                    @endif

                                                    <div class="d-flex gap-2 mt-3">
                                                        <a href="{{ route('demandes-epaves.show', $demande) }}"
                                                           class="btn btn-sm btn-outline-primary flex-fill">
                                                            <i class="fas fa-eye"></i> Détails
                                                        </a>
                                                        @if($offreAcceptee && $offreAcceptee->user)
                                                            <a href="{{ route('messages.conversation', ['userId' => $offreAcceptee->user->id]) }}"
                                                               class="btn btn-sm btn-success flex-fill">
                                                                <i class="fas fa-envelope"></i> Contacter
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .nav-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
        }
    </style>

    @push('scripts')
        <script>
            // Sauvegarder l'onglet actif dans le localStorage
            document.addEventListener('DOMContentLoaded', function() {
                const tabButtons = document.querySelectorAll('#stockTabs button[data-bs-toggle="tab"]');
                const savedTab = localStorage.getItem('activeStockTab');

                // Restaurer l'onglet sauvegardé
                if (savedTab) {
                    const tabToActivate = document.querySelector(`#stockTabs button[data-bs-target="${savedTab}"]`);
                    if (tabToActivate) {
                        const tab = new bootstrap.Tab(tabToActivate);
                        tab.show();
                    }
                }

                // Sauvegarder l'onglet actif au clic
                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        localStorage.setItem('activeStockTab', this.getAttribute('data-bs-target'));
                    });
                });
            });
        </script>
    @endpush
@endsection
