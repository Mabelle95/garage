@extends('layouts.app')

@section('title', 'Gestion des véhicules')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestion des véhicules</h1>
            <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un véhicule
            </a>
        </div>

        <!-- Filtres -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary w-100">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des véhicules -->
        <div class="card shadow">
            <div class="card-body">
                @if($vehicles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Marque/Modèle</th>
                                <th>Année</th>
                                <th>Plaque</th>
                                <th>État</th>
                                <th>Prix</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicles as $vehicle)
                                <tr>
                                    <td>
                                        @if($vehicle->photo_principale)
                                            <img src="{{ asset('storage/' . $vehicle->photo_principale) }}"
                                                 width="60" height="60" style="object-fit: cover;" class="rounded">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-car text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $vehicle->marque }} {{ $vehicle->modele }}</strong><br>
                                        <small class="text-muted">{{ $vehicle->carburant }} • {{ $vehicle->transmission }}</small>
                                    </td>
                                    <td>{{ $vehicle->annee }}</td>
                                    <td>{{ $vehicle->numero_plaque }}</td>
                                    <td>
                                    <span class="badge bg-{{ $vehicle->etat === 'bon' ? 'success' : ($vehicle->etat === 'moyen' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($vehicle->etat) }}
                                    </span>
                                    </td>
                                    <td>{{ number_format($vehicle->prix_epave, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Supprimer ce véhicule ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $vehicles->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-car fa-4x text-muted mb-3"></i>
                        <h4>Aucun véhicule enregistré</h4>
                        <p class="text-muted">Commencez par ajouter votre premier véhicule</p>
                        <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un véhicule
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
