@extends('layouts.casse')

@section('title', 'Gestion des commandes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des commandes</h1>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Commandes totales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $commandes->total() }}</div>
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
                                En attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $commandes->where('statut', 'en_attente')->count() }}
                            </div>
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
                                En cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $commandes->whereIn('statut', ['confirmee', 'en_preparation', 'expediee'])->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
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
                                Livrées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $commandes->where('statut', 'livree')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
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
                            <th>Client</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($commandes as $commande)
                            <tr>
                                <td><strong>{{ $commande->numero_commande }}</strong></td>
                                <td>{{ $commande->client?->name ?? 'Utilisateur supprimé' }}</td>
                                <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($commande->total, 2, ',', ' ') }} FCFA</td>
                                <td>
                                    <span class="badge bg-{{ $commande->statut === 'livree' ? 'success' :
                                        ($commande->statut === 'annulee' ? 'danger' :
                                        ($commande->statut === 'en_attente' ? 'warning' : 'info')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('gestion.commandes.show', $commande) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Détails
                                        </a>
                                         <a href="#" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editStatutModal{{ $commande->id }}">
                                                    <i class="fas fa-pen"></i> Approuver
                                                </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $commandes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4>Aucune commande</h4>
                    <p class="text-muted">Aucune commande n'a été passée pour le moment</p>
                </div>
            @endif
        </div>
    </div>

      {{-- Modal --}}
    <div class="modal fade" id="editStatutModal{{ $commande->id }}" tabindex="-1" aria-labelledby="editStatutLabel{{ $commande->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="editStatutLabel{{ $commande->id }}">Modifier le statut</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <!-- Formulaire -->
      <form action="{{ route('gestion.commandes.update-statut', $commande) }}" method="POST">
          @csrf
          @method("PUT")

          <div class="modal-body">
              <div class="mb-3">
                  <label for="statut" class="form-label">Statut</label>
                  <select id="statut" name="statut" class="form-select" required>
                      <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                      <option value="en_cours" {{ $commande->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                      <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}>Livrée</option>
                      <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                  </select>
              </div>
          </div>

          <!-- Footer -->
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection
