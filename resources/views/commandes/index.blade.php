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
