@extends('layouts.casse')

@section('title', 'Tableau de bord - Casse')

@section('casse-content')
    <div class="row">
        <!-- Statistiques -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Véhicules en stock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['vehicules'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pièces disponibles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pieces'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Commandes ce mois</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['commandes_mois'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Chiffre d'affaires</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['chiffre_affaires_mois'] ?? 0, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Véhicules récents -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Véhicules récents</h6>
                    <a href="{{ route('pieces.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Ajouter
                    </a>
                </div>
                <div class="card-body">
                    @if ($recentVehicles && $recentVehicles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Marque/Modèle</th>
                                        <th>Année</th>
                                        <th>État</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentVehicles as $vehicle)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($vehicle->photo_principale)
                                                        <img src="{{ asset('storage/' . $vehicle->photo_principale) }}"
                                                            class="rounded me-3" width="40" height="40"
                                                            style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="fas fa-car text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $vehicle->marque }} {{ $vehicle->modele }}</strong>
                                                        <div class="text-muted small">{{ $vehicle->numero_plaque }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $vehicle->annee }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $vehicle->etat === 'bon' ? 'success' : ($vehicle->etat === 'moyen' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($vehicle->etat) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('pieces.show', $vehicle) }}"
                                                        class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pieces.edit', $vehicle) }}"
                                                        class="btn btn-outline-secondary">
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
                        <div class="text-center py-4">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun véhicule enregistré</p>
                            <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter un véhicule
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Commandes récentes -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Commandes récentes</h6>
                    <span class="badge bg-warning">{{ $commandesEnAttente ?? 0 }} en attente</span>
                </div>
                <div class="card-body">
                    @if ($recentCommandes && $recentCommandes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentCommandes as $commande)
                                        <tr>
                                            <td>
                                                <strong>{{ $commande->numero_commande }}</strong>
                                                <div class="text-muted small">{{ $commande->created_at->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td>{{ $commande->client ? $commande->client->name : 'Utilisateur supprimé' }}
                                            </td>
                                            <td>{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $commande->statut === 'livree' ? 'success' : ($commande->statut === 'en_attente' ? 'warning' : 'info') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{-- <form action="{{ route('gestion.commandes.update-statut', $commande) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </form> --}}

                                                {{-- <a href={{ route('gestion.commandes.edit-statut', $commande) }}
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> --}}
                                                </a>
                                                <a href="#" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editStatutModal{{ $commande->id }}">
                                                    <i class="fas fa-pen"></i>
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
                            <p class="text-muted">Aucune commande récente</p>
                        </div>
                    @endif
                </div>
            </div>
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
