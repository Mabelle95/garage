@extends('layouts.app')

@section('title', 'Détails de l\'annonce')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Détails de l'annonce</h1>
                <span class="badge {{ $demandeEpave->type_badge_class }} fs-5">
                    @if($demandeEpave->type === 'vehicule')
                        <i class="fas fa-car"></i>
                    @else
                        <i class="fas fa-car-crash"></i>
                    @endif
                    {{ $demandeEpave->type_libelle }}
                </span>
            </div>
            <a href="{{ route('demandes-epaves.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">{{ $demandeEpave->marque }} {{ $demandeEpave->modele }} ({{ $demandeEpave->annee }})</h5>
                        <span class="badge {{ $demandeEpave->statut_badge_class }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $demandeEpave->statut)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Photos -->
                        @if($demandeEpave->photos && count($demandeEpave->photos) > 0)
                            <div class="mb-4">
                                <h6>Photos du véhicule</h6>
                                <div class="row">
                                    @foreach($demandeEpave->photos as $photo)
                                        <div class="col-md-3 mb-3">
                                            <img src="{{ asset('storage/' . $photo) }}"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="cursor: pointer"
                                                 onclick="openModal('{{ asset('storage/' . $photo) }}')">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Informations techniques -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Type:</th>
                                        <td>
                                            <span class="badge {{ $demandeEpave->type_badge_class }}">
                                                {{ $demandeEpave->type_libelle }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Marque/Modèle:</th>
                                        <td>{{ $demandeEpave->marque }} {{ $demandeEpave->modele }}</td>
                                    </tr>
                                    <tr>
                                        <th>Année:</th>
                                        <td>{{ $demandeEpave->annee }}</td>
                                    </tr>
                                    <tr>
                                        <th>Carburant:</th>
                                        <td>{{ ucfirst($demandeEpave->carburant) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kilométrage:</th>
                                        <td>{{ number_format($demandeEpave->kilometrage, 0, ',', ' ') }} km</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Couleur:</th>
                                        <td>{{ $demandeEpave->couleur }}</td>
                                    </tr>
                                    <tr>
                                        <th>État:</th>
                                        <td>{{ ucfirst($demandeEpave->etat) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Prix souhaité:</th>
                                        <td>
                                            @if($demandeEpave->prix_souhaite)
                                                <strong class="text-primary">{{ number_format($demandeEpave->prix_souhaite, 0, ',', ' ') }} FCFA</strong>
                                            @else
                                                <span class="text-muted">Non spécifié</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Numéro chassis:</th>
                                        <td>{{ $demandeEpave->numero_chassis }}</td>
                                    </tr>
                                    <tr>
                                        <th>Numéro plaque:</th>
                                        <td>{{ $demandeEpave->numero_plaque }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h6>Description</h6>
                            <p class="text-muted">{{ $demandeEpave->description }}</p>
                        </div>

                        <!-- Informations de contact -->
                        <div class="mb-4">
                            <h6>Informations de contact</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><i class="fas fa-phone me-2"></i> {{ $demandeEpave->telephone_contact }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><i class="fas fa-map-marker-alt me-2"></i> {{ $demandeEpave->adresse }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offres -->
                @if(
                    (auth()->user()->id === $demandeEpave->user_id) ||
                    $demandeEpave->offres->where('user_id', auth()->id())->count() > 0
                )
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="m-0">
                                @if(auth()->user()->id === $demandeEpave->user_id)
                                    Offres reçues ({{ $demandeEpave->offres->whereIn('statut', ['en_attente'])->count() }} en attente)
                                @else
                                    Mes offres
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                // Si c'est le propriétaire, afficher toutes les offres
                                // Sinon, afficher uniquement les offres de l'utilisateur
                                $offresAffichees = auth()->user()->id === $demandeEpave->user_id
                                    ? $demandeEpave->offres
                                    : $demandeEpave->offres->where('user_id', auth()->id());
                            @endphp

                            @if($offresAffichees->count() > 0)
                                @foreach($offresAffichees->sortByDesc('created_at') as $offre)
                                    <div class="card mb-3 border-{{ $offre->statut === 'accepte' ? 'success' : ($offre->statut === 'refuse' ? 'danger' : 'warning') }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-2">
                                                        @if(auth()->user()->id === $demandeEpave->user_id)
                                                            {{ $offre->user->name }}
                                                            <span class="badge bg-{{ $offre->user->role->value === 'casse' ? 'success' : 'primary' }} ms-2">
                                                                {{ ucfirst($offre->user->role->value) }}
                                                            </span>
                                                        @else
                                                            Votre offre
                                                        @endif
                                                    </h6>

                                                    <p class="mb-2">
                                                        <strong class="text-primary fs-5">{{ number_format($offre->prix_offert, 0, ',', ' ') }} FCFA</strong>
                                                    </p>

                                                    @if($offre->message)
                                                        <p class="mb-2 text-muted small">
                                                            <i class="fas fa-comment"></i> {{ $offre->message }}
                                                        </p>
                                                    @endif

                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i> {{ $offre->created_at->diffForHumans() }}
                                                    </small>
                                                </div>

                                                <div class="ms-3">
                                                    @if($offre->statut === 'accepte')
                                                        <span class="badge bg-success">Acceptée</span>
                                                    @elseif($offre->statut === 'refuse')
                                                        <span class="badge bg-danger">Refusée</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">En attente</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Actions propriétaire (sans classes JavaScript) -->
                                            @if(auth()->user()->id === $demandeEpave->user_id && $offre->statut === 'en_attente')
                                                <div class="mt-3 d-flex gap-2">
                                                    <!-- CORRECTION: Formulaire NORMAL sans JavaScript -->
                                                    <form action="{{ route('demandes-epaves.accepter-offre', [$demandeEpave, $offre]) }}"
                                                          method="POST"
                                                          style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-success btn-sm"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir accepter cette offre ? Les autres offres seront automatiquement refusées.')">
                                                            <i class="fas fa-check"></i> Accepter
                                                        </button>
                                                    </form>

                                                    <!-- CORRECTION: Formulaire NORMAL sans JavaScript -->
                                                    <form action="{{ route('demandes-epaves.refuser-offre', [$demandeEpave, $offre]) }}"
                                                          method="POST"
                                                          style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir refuser cette offre ?')">
                                                            <i class="fas fa-times"></i> Refuser
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif

                                            <!-- Actions acheteur -->
                                            @if(auth()->user()->id !== $demandeEpave->user_id && $offre->statut === 'en_attente')
                                                <div class="mt-3">
                                                    <form action="{{ route('demandes-epaves.retirer-offre', [$demandeEpave, $offre]) }}"
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-outline-danger btn-sm"
                                                                onclick="return confirm('Voulez-vous vraiment retirer votre offre ?')">
                                                            <i class="fas fa-trash"></i> Retirer l'offre
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Aucune offre pour le moment</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->id === $demandeEpave->user_id)
                            <!-- Actions propriétaire -->
                            @if($demandeEpave->statut === 'en_attente')
                                <a href="{{ route('demandes-epaves.edit', $demandeEpave) }}"
                                   class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-edit"></i> Modifier l'annonce
                                </a>

                                <form action="{{ route('demandes-epaves.destroy', $demandeEpave) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger w-100"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                                        <i class="fas fa-trash"></i> Supprimer l'annonce
                                    </button>
                                </form>
                            @endif
                        @else
                            <!-- Actions pour les acheteurs potentiels -->

                            @if($offreRefusee)
                                <!-- Cas : L'utilisateur a une offre refusée -->
                                <div class="alert alert-warning mb-3">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Offre précédente refusée</h6>
                                    <p class="small mb-2">Votre offre de <strong>{{ number_format($offreRefusee->prix_offert, 0, ',', ' ') }} FCFA</strong> a été refusée.</p>
                                    <p class="small mb-0">Vous pouvez faire une nouvelle offre avec un prix différent.</p>
                                </div>
                            @endif

                            @if($peutFaireOffre && $demandeEpave->statut === 'en_attente')
                                <!-- Formulaire pour faire une offre (ou refaire une offre) -->
                                <div id="faire-offre">
                                    <h6>
                                        @if($offreRefusee)
                                            <i class="fas fa-sync-alt"></i> Faire une nouvelle offre
                                        @else
                                            <i class="fas fa-gavel"></i> Faire une offre
                                        @endif
                                    </h6>

                                    @if($offreRefusee)
                                        <div class="alert alert-info alert-sm mb-3">
                                            <small>
                                                <i class="fas fa-info-circle"></i>
                                                Votre ancienne offre sera remplacée par la nouvelle.
                                            </small>
                                        </div>
                                    @endif

                                    <form action="{{ route('demandes-epaves.faire-offre', $demandeEpave) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="prix_offert" class="form-label">
                                                Prix offert (FCFA) *
                                                @if($demandeEpave->prix_souhaite)
                                                    <small class="text-muted">(Prix souhaité : {{ number_format($demandeEpave->prix_souhaite, 0, ',', ' ') }} FCFA)</small>
                                                @endif
                                            </label>
                                            <input type="number"
                                                   step="0.01"
                                                   class="form-control"
                                                   id="prix_offert"
                                                   name="prix_offert"
                                                   required
                                                   min="1"
                                                   @if($offreRefusee)
                                                       value="{{ $offreRefusee->prix_offert }}"
                                                   placeholder="Proposez un nouveau prix"
                                                   @else
                                                       placeholder="Votre offre en FCFA"
                                                @endif>
                                            @if($offreRefusee)
                                                <div class="form-text">
                                                    <i class="fas fa-lightbulb"></i>
                                                    Ancienne offre : {{ number_format($offreRefusee->prix_offert, 0, ',', ' ') }} FCFA
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label for="message" class="form-label">Message (optionnel)</label>
                                            <textarea class="form-control"
                                                      id="message"
                                                      name="message"
                                                      rows="3"
                                                      placeholder="Précisions sur votre offre...">@if($offreRefusee){{ $offreRefusee->message }}@endif</textarea>
                                            @if($offreRefusee)
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle"></i>
                                                    Expliquez pourquoi vous faites une nouvelle offre
                                                </div>
                                            @endif
                                        </div>

                                        <button type="submit" class="btn btn-warning w-100">
                                            @if($offreRefusee)
                                                <i class="fas fa-sync-alt"></i> Soumettre la nouvelle offre
                                            @else
                                                <i class="fas fa-gavel"></i> Soumettre l'offre
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @elseif(!$peutFaireOffre && $demandeEpave->statut === 'en_attente' && !$offreRefusee)
                                <!-- L'utilisateur a déjà une offre en attente -->
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Vous avez déjà fait une offre sur cette annonce.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Informations vendeur -->
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0">Informations du vendeur</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ $demandeEpave->user?->name ?? 'Utilisateur inconnu' }}</strong></p>
                        <p class="mb-1">
                <span class="badge bg-{{ $demandeEpave->user->role->value === 'casse' ? 'success' : 'primary' }}">
                    {{ ucfirst($demandeEpave->user->role->value) }}
                </span>
                        </p>
                        <p class="mb-1 text-muted small">
                            <i class="fas fa-phone"></i> {{ $demandeEpave->telephone_contact }}
                        </p>
                        <p class="mb-0 text-muted small">
                            <i class="fas fa-map-marker-alt"></i> {{ $demandeEpave->adresse }}
                        </p>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Modal pour les photos -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>
@endsection
