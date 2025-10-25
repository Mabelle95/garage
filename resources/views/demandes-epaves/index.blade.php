@extends('layouts.app')

@section('title', 'Demandes de véhicules et épaves')

@section('content')
    <div class="container-fluid">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-tab {{ request('tab') !== 'disponibles' ? 'active' : '' }}"
                        data-bs-toggle="tab" data-bs-target="#mes-demandes" type="button">
                    <i class="fas fa-list me-2"></i> Mes annonces
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-tab {{ request('tab') === 'disponibles' ? 'active' : '' }}"
                        data-bs-toggle="tab" data-bs-target="#demandes-disponibles" type="button">
                    <i class="fas fa-search-dollar me-2"></i> Annonces disponibles
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ✅ Onglet Mes Annonces --}}
            <div class="tab-pane fade {{ request('tab') !== 'disponibles' ? 'show active' : '' }}" id="mes-demandes">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Mes annonces</h1>
                    <a href="{{ route('demandes-epaves.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle annonce
                    </a>
                </div>

                <div class="card shadow">
                    <div class="card-body">
                        @if($mesDemandes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Véhicule</th>
                                        <th>Prix souhaité</th>
                                        <th>État</th>
                                        <th>Offres reçues</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($mesDemandes as $demande)
                                        <tr>
                                            <td>
                                            <span class="badge {{ $demande->type_badge_class }}">
                                                @if($demande->type === 'vehicule')
                                                    <i class="fas fa-car"></i>
                                                @else
                                                    <i class="fas fa-car-crash"></i>
                                                @endif
                                                {{ $demande->type_libelle }}
                                            </span>
                                            </td>
                                            <td>
                                                <strong>{{ $demande->marque }} {{ $demande->modele }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $demande->annee }} • {{ $demande->carburant }} • {{ number_format($demande->kilometrage, 0, ',', ' ') }} km
                                                </small>
                                            </td>
                                            <td>
                                                @if($demande->prix_souhaite)
                                                    <strong>{{ number_format($demande->prix_souhaite, 0, ',', ' ') }} FCFA</strong>
                                                @else
                                                    <span class="text-muted">Non spécifié</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($demande->etat) }}</span>
                                            </td>
                                            <td>
                                            <span class="badge bg-{{ $demande->offres->count() > 0 ? 'success' : 'secondary' }}">
                                                {{ $demande->offres->count() }} offre(s)
                                            </span>
                                            </td>
                                            <td>
                                                @if($demande->statut === 'vendu')
                                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Vendu</span>
                                                @elseif($demande->statut === 'accepte')
                                                    <span class="badge bg-info"><i class="fas fa-handshake"></i> Offre acceptée</span>
                                                @else
                                                    <span class="badge {{ $demande->statut_badge_class }}">
                                                    {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('demandes-epaves.show', $demande) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if(in_array($demande->statut, ['vendu', 'accepte']))
                                                        @php
                                                            $offreAcceptee = $demande->offres->where('statut', 'accepte')->first();
                                                        @endphp
                                                        @if($offreAcceptee && $offreAcceptee->user)
                                                            <a href="{{ route('messages.index') }}"
                                                               class="btn btn-sm btn-outline-success"
                                                               title="Contacter l'acheteur">
                                                                <i class="fas fa-comment-dots"></i>
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
                            <div class="d-flex justify-content-center mt-4">
                                {{ $mesDemandes->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-car-crash fa-4x text-muted mb-3"></i>
                                <h4>Aucune annonce</h4>
                                <p class="text-muted">Vous n'avez pas encore créé d'annonce de vente.</p>
                                <a href="{{ route('demandes-epaves.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Créer une annonce
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ✅ Onglet Annonces Disponibles --}}
            <div class="tab-pane fade {{ request('tab') === 'disponibles' ? 'show active' : '' }}" id="demandes-disponibles">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Véhicules et épaves disponibles</h1>
                </div>

                {{-- Filtres --}}
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="tab" value="disponibles">

                            <div class="col-md-2">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="vehicule" {{ request('type') === 'vehicule' ? 'selected' : '' }}>Véhicules</option>
                                    <option value="epave" {{ request('type') === 'epave' ? 'selected' : '' }}>Épaves</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Marque</label>
                                <input type="text" name="marque" class="form-control" placeholder="Marque..." value="{{ request('marque') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Prix maximum</label>
                                <input type="number" name="prix_max" class="form-control" placeholder="Prix max..." value="{{ request('prix_max') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <a href="{{ route('demandes-epaves.index', ['tab' => 'disponibles']) }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-redo"></i> Réinitialiser
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Liste des annonces disponibles --}}
                <div class="card shadow">
                    <div class="card-body">
                        @if($autresDemandes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Véhicule</th>
                                        <th>Vendeur</th>
                                        <th>Prix souhaité</th>
                                        <th>État</th>
                                        <th>Offres</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($autresDemandes as $demande)
                                        <tr>
                                            <td>
                                            <span class="badge {{ $demande->type_badge_class }}">
                                                @if($demande->type === 'vehicule')
                                                    <i class="fas fa-car"></i>
                                                @else
                                                    <i class="fas fa-car-crash"></i>
                                                @endif
                                                {{ $demande->type_libelle }}
                                            </span>
                                            </td>
                                            <td>
                                                <strong>{{ $demande->marque }} {{ $demande->modele }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $demande->annee }} • {{ $demande->carburant }} • {{ number_format($demande->kilometrage, 0, ',', ' ') }} km
                                                </small>
                                            </td>
                                            <td>
                                                {{ $demande->user->name }}
                                                <span class="badge bg-{{ $demande->user->role->value === 'casse' ? 'success' : 'primary' }}">
                                                {{ ucfirst($demande->user->role->value) }}
                                            </span>
                                            </td>
                                            <td>
                                                @if($demande->prix_souhaite)
                                                    <strong>{{ number_format($demande->prix_souhaite, 0, ',', ' ') }} FCFA</strong>
                                                @else
                                                    <span class="text-muted">Non spécifié</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-secondary">{{ ucfirst($demande->etat) }}</span></td>
                                            <td>
                                            <span class="badge bg-{{ $demande->offres->count() > 0 ? 'success' : 'secondary' }}">
                                                {{ $demande->offres->count() }} offre(s)
                                            </span>
                                            </td>
                                            <td>
                                                @if($demande->statut === 'vendu')
                                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Vendu</span>
                                                @elseif($demande->statut === 'accepte')
                                                    <span class="badge bg-info"><i class="fas fa-handshake"></i> Offre acceptée</span>
                                                @else
                                                    <span class="badge {{ $demande->statut_badge_class }}">
                                                    {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('demandes-epaves.show', $demande) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    {{-- ✅ Lien vers le chat --}}
                                                    <a href="{{ route('messages.conversation', ['userId' => $demande->user->id]) }}"
                                                       class="btn btn-sm btn-outline-success" title="Contacter le vendeur">
                                                        <i class="fas fa-comment-dots"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $autresDemandes->appends(['tab' => 'disponibles'])->links() }}
                            </div>

                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                                <h4>Aucune annonce disponible</h4>
                                <p class="text-muted">Aucun véhicule ou épave n'est actuellement disponible.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-group {
            display: flex;
            gap: 5px;
        }
        .badge {
            white-space: nowrap;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@endsection
