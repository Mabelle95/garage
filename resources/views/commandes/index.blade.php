@extends('layouts.app')

@section('title', auth()->user()->isClient() ? 'Mes commandes' : 'Gestion des commandes')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ auth()->user()->isClient() ? 'Mes commandes' : 'Gestion des commandes' }}</h1>
        </div>

        <!-- Filtres -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            @foreach(['en_attente','confirmee','en_preparation','expedie','livree','annulee'] as $statut)
                                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('commandes.index') }}" class="btn btn-secondary w-100">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="card shadow">
            <div class="card-body">
                @if($commandes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($commandes as $commande)
                                <tr>
                                    <td><a href="{{ route('commandes.show', $commande) }}"><strong>{{ $commande->numero_commande }}</strong></a></td>
                                    <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($commande->total, 2, ',', ' ') }} FCFA</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $commande->statut === 'livree' ? 'success' :
                                            ($commande->statut === 'annulee' ? 'danger' :
                                            ($commande->statut === 'en_attente' ? 'warning' : 'info')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $commande->statut_paiement === 'paye' ? 'success' : 'warning' }}">
                                            {{ ucfirst(str_replace('_', ' ', $commande->statut_paiement)) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($commande->statut === 'annulee')
                                            <span class="text-danger fw-bold">Commande annulée</span>
                                        @else
                                            <div class="btn-group">
                                                <!-- Bouton Messagerie -->
                                                <button class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#messageModal{{ $commande->id }}"
                                                        title="Envoyer un message">
                                                    <i class="fas fa-envelope"></i>
                                                </button>

                                                @if(auth()->user()->isCasse())
                                                    <!-- Modifier le statut depuis le modal -->
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editStatutModal{{ $commande->id }}">
                                                        <i class="fas fa-pen"></i> Modifier
                                                    </button>
                                                @endif

                                                @if(auth()->user()->isClient() && in_array($commande->statut, ['en_attente','confirmee']))
                                                    @php
                                                        $piecesSupprimees = $commande->items->filter(function($item) {
                                                            return is_null($item->piece);
                                                        });
                                                        $peutAnnuler = $piecesSupprimees->isEmpty();
                                                    @endphp

                                                    @if($peutAnnuler)
                                                        <form action="{{ route('commandes.annuler', $commande) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Annuler cette commande ?')">
                                                                <i class="fas fa-times"></i> Annuler
                                                            </button>
                                                        </form>
                                                        {{-- <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#annulerModal{{ $commande->id }}">
                                                            <i class="fas fa-times"></i> Annuler
                                                        </button> --}}
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                                                data-bs-toggle="tooltip"
                                                                title="Impossible d'annuler : certaines pièces de cette commande ne sont plus disponibles">
                                                            <i class="fas fa-exclamation-triangle"></i> Non annulable
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Affichage d'un message si des pièces ont été supprimées -->
                                @if(auth()->user()->isClient() && in_array($commande->statut, ['en_attente','confirmee']))
                                    @php
                                        $piecesSupprimees = $commande->items->filter(function($item) {
                                            return is_null($item->piece);
                                        });
                                    @endphp

                                    @if($piecesSupprimees->isNotEmpty())
                                        <tr>
                                            <td colspan="6" class="bg-warning bg-opacity-10">
                                                <div class="alert alert-warning mb-0 py-2">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <strong>Attention :</strong> Cette commande contient {{ $piecesSupprimees->count() }} pièce(s) qui ne sont plus disponibles dans le catalogue.
                                                    L'annulation n'est plus possible. Veuillez contacter le service client pour plus d'informations.
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endif

                                <!-- Modal pour modification du statut (uniquement pour casse) -->
                                @if(auth()->user()->isCasse())
                                    <div class="modal fade" id="editStatutModal{{ $commande->id }}" tabindex="-1" aria-labelledby="editStatutLabel{{ $commande->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editStatutLabel{{ $commande->id }}">Modifier le statut</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <form action="{{ route('gestion.commandes.update-statut', $commande) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="statut{{ $commande->id }}" class="form-label">Statut</label>
                                                            <select id="statut{{ $commande->id }}" name="statut" class="form-select" required>
                                                                @foreach(['en_attente','confirmee','en_preparation','expedie','livree','annulee'] as $statut)
                                                                    <option value="{{ $statut }}" {{ $commande->statut === $statut ? 'selected' : '' }}>
                                                                        {{ ucfirst(str_replace('_', ' ', $statut)) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="commentaire{{ $commande->id }}" class="form-label">Commentaire</label>
                                                            <textarea id="commentaire{{ $commande->id }}" name="commentaire" class="form-control">{{ old('commentaire') }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Modal Messagerie -->
                                <div class="modal fade" id="messageModal{{ $commande->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-envelope me-2"></i>
                                                    Envoyer un message concernant la commande {{ $commande->numero_commande }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('messages.envoyer-rapide') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="commande_id" value="{{ $commande->id }}">
                                                @php
                                                    // Déterminer le destinataire selon le rôle de l'utilisateur
                                                    if (auth()->user()->isClient()) {
                                                        // Si client, chercher la casse de la première pièce
                                                        $destinataire = $commande->items->first()?->piece?->user;
                                                        $destinataireId = $destinataire?->id;
                                                    } else {
                                                        // Si casse, envoyer au client
                                                        $destinataire = $commande->user;
                                                        $destinataireId = $commande->user_id;
                                                    }
                                                @endphp
                                                <input type="hidden" name="destinataire_id" value="{{ $destinataireId }}">

                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        <strong>Destinataire :</strong>
                                                        {{ $destinataire?->name ?? 'Non disponible' }}
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="sujet{{ $commande->id }}" class="form-label">Sujet *</label>
                                                        <input type="text"
                                                               class="form-control"
                                                               id="sujet{{ $commande->id }}"
                                                               name="sujet"
                                                               value="Concernant la commande {{ $commande->numero_commande }}"
                                                               required
                                                               maxlength="255">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="contenu{{ $commande->id }}" class="form-label">Message *</label>
                                                        <textarea class="form-control"
                                                                  id="contenu{{ $commande->id }}"
                                                                  name="contenu"
                                                                  rows="6"
                                                                  required
                                                                  maxlength="5000"
                                                                  placeholder="Écrivez votre message ici..."></textarea>
                                                        <small class="text-muted">Maximum 5000 caractères</small>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i> Fermer
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-paper-plane me-1"></i> Envoyer le message
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Annulation avec motif (pour clients) -->
                                @if(auth()->user()->isClient() && in_array($commande->statut, ['en_attente','confirmee']))
                                    @php
                                        $piecesSupprimees = $commande->items->filter(function($item) {
                                            return is_null($item->piece);
                                        });
                                        $peutAnnuler = $piecesSupprimees->isEmpty();
                                    @endphp

                                    @if($peutAnnuler)
                                        <div class="modal fade" id="annulerModal{{ $commande->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Annuler la commande
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('commandes.annuler', $commande) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <div class="modal-body">
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-info-circle me-2"></i>
                                                                Vous êtes sur le point d'annuler la commande <strong>{{ $commande->numero_commande }}</strong>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="motif_annulation{{ $commande->id }}" class="form-label">
                                                                    Motif d'annulation *
                                                                </label>
                                                                <textarea class="form-control"
                                                                          id="motif_annulation{{ $commande->id }}"
                                                                          name="motif_annulation"
                                                                          rows="4"
                                                                          required
                                                                          minlength="10"
                                                                          maxlength="500"
                                                                          placeholder="Veuillez expliquer la raison de l'annulation (minimum 10 caractères)..."></textarea>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-info-circle"></i>
                                                                    Ce motif sera envoyé au vendeur par messagerie (10-500 caractères).
                                                                </small>
                                                            </div>

                                                            <div class="alert alert-info mb-0">
                                                                <small>
                                                                    <i class="fas fa-lightbulb me-1"></i>
                                                                    Un message sera automatiquement envoyé à
                                                                    @php
                                                                        $casseName = $commande->items->first()?->piece?->user?->name ?? 'le vendeur';
                                                                    @endphp
                                                                    <strong>{{ $casseName }}</strong>
                                                                    avec votre motif d'annulation.
                                                                </small>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-1"></i> Fermer
                                                            </button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-ban me-1"></i> Confirmer l'annulation
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $commandes->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h4>Aucune commande</h4>
                        <p class="text-muted">Aucune commande disponible pour le moment</p>
                        <a href="{{ route('pieces.index') }}" class="btn btn-primary">
                            <i class="fas fa-cog"></i> Parcourir les pièces
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Initialiser les tooltips Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>
    @endpush
@endsection
