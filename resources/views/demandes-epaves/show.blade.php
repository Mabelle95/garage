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
                <!-- Section Offres dans show.blade.php -->
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
                                    Mon offre
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($demandeEpave->offres->count() > 0)
                                <!-- Filtres pour le propriétaire -->
                                @if(auth()->user()->id === $demandeEpave->user_id && $demandeEpave->offres->count() > 3)
                                    <div class="btn-group btn-group-sm mb-3" role="group">
                                        <button type="button" class="btn btn-outline-primary active" onclick="filterOffres('all')">
                                            Toutes ({{ $demandeEpave->offres->count() }})
                                        </button>
                                        <button type="button" class="btn btn-outline-success" onclick="filterOffres('en_attente')">
                                            En attente ({{ $demandeEpave->offres->where('statut', 'en_attente')->count() }})
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="filterOffres('refuse')">
                                            Refusées ({{ $demandeEpave->offres->where('statut', 'refuse')->count() }})
                                        </button>
                                    </div>
                                @endif

                                <div class="list-group">
                                    @foreach($demandeEpave->offres->sortByDesc('created_at') as $offre)
                                        @if(auth()->user()->id === $demandeEpave->user_id || $offre->user_id === auth()->id())
                                            <div class="list-group-item offre-item offre-{{ $offre->statut }} {{ $offre->statut === 'refuse' ? 'bg-light' : '' }}"
                                                 data-statut="{{ $offre->statut }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            {{ $offre->user->name }}
                                                            <span class="badge bg-{{ $offre->user->role->value === 'casse' ? 'success' : 'primary' }}">
                                                {{ ucfirst($offre->user->role->value) }}
                                            </span>
                                                            @if($offre->user->role->value === 'casse' && $offre->user->nom_entreprise)
                                                                <small class="text-muted">({{ $offre->user->nom_entreprise }})</small>
                                                            @endif
                                                        </h6>
                                                        <p class="mb-1">
                                                            <strong class="text-success fs-5">{{ number_format($offre->prix_offert, 0, ',', ' ') }} FCFA</strong>
                                                        </p>
                                                        @if($offre->message)
                                                            <p class="mb-1 text-muted">{{ $offre->message }}</p>
                                                        @endif
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock"></i> {{ $offre->created_at->format('d/m/Y à H:i') }}
                                                        </small>
                                                    </div>
                                                    <div class="ms-3">
                                                        @if($demandeEpave->statut === 'en_attente' && $offre->statut === 'en_attente')
                                                            @if(auth()->user()->id === $demandeEpave->user_id)
                                                                <!-- Boutons pour le propriétaire : Accepter et Refuser -->
                                                                <div class="btn-group-vertical" role="group">
                                                                    <form action="{{ route('demandes-epaves.accepter-offre', ['demandeEpave' => $demandeEpave->id, 'offre' => $offre->id]) }}"
                                                                          method="POST" class="mb-1">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-success btn-sm w-100"
                                                                                onclick="return confirm('Accepter cette offre de {{ number_format($offre->prix_offert, 0, ',', ' ') }} FCFA?')">
                                                                            <i class="fas fa-check"></i> Accepter
                                                                        </button>
                                                                    </form>

                                                                    <form action="{{ route('demandes-epaves.refuser-offre', ['demandeEpave' => $demandeEpave->id, 'offre' => $offre->id]) }}"
                                                                          method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100"
                                                                                onclick="return confirm('Refuser cette offre ? L\'acheteur pourra faire une nouvelle offre.')">
                                                                            <i class="fas fa-times"></i> Refuser
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endif

                                                            @if($offre->user_id === auth()->id())
                                                                <!-- Bouton pour retirer sa propre offre -->
                                                                <form action="{{ route('demandes-epaves.retirer-offre', ['demandeEpave' => $demandeEpave->id, 'offre' => $offre->id]) }}"
                                                                      method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-warning btn-sm"
                                                                            onclick="return confirm('Retirer votre offre ?')">
                                                                        <i class="fas fa-undo"></i> Retirer
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @elseif($offre->statut === 'accepte')
                                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check-circle"></i> Offre acceptée
                                            </span>
                                                        @elseif($offre->statut === 'refuse')
                                                            <div class="text-end">
                                                <span class="badge bg-danger fs-6 d-block mb-2">
                                                    <i class="fas fa-times-circle"></i> Offre refusée
                                                </span>
                                                                @if($offre->user_id === auth()->id() && $demandeEpave->statut === 'en_attente')
                                                                    <a href="#faire-offre" class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-sync-alt"></i> Refaire une offre
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune offre pour le moment</p>
                                </div>
                            @endif
                        </div>
                    </div>

                        <script>
                            function filterOffres(statut) {
                                const offres = document.querySelectorAll('.offre-item');
                                const buttons = document.querySelectorAll('.btn-group button');

                                // Mettre à jour les boutons actifs
                                buttons.forEach(btn => btn.classList.remove('active'));
                                event.target.classList.add('active');

                                // Filtrer les offres
                                offres.forEach(offre => {
                                    if (statut === 'all') {
                                        offre.style.display = 'block';
                                    } else {
                                        if (offre.dataset.statut === statut) {
                                            offre.style.display = 'block';
                                        } else {
                                            offre.style.display = 'none';
                                        }
                                    }
                                });
                            }
                        </script>
                @endif



            </div>


            <!-- Sidebar -->
            <!-- Sidebar - Section Actions -->
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0">Actions</h6>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->id === $demandeEpave->user_id)
                            <!-- Actions pour le propriétaire -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('demandes-epaves.edit', $demandeEpave) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i> Modifier l'annonce
                                </a>

                                @if($demandeEpave->statut === 'en_attente')
                                    <form action="{{ route('demandes-epaves.destroy', $demandeEpave) }}" method="POST" class="d-grid">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('Supprimer cette annonce ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                @endif
                            </div>
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
