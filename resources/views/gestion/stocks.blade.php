@extends('layouts.app')

@section('title', 'Gestion des stocks')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestion des stocks</h1>
            <a href="{{ route('pieces.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une pièce
            </a>
        </div>

        <!-- Statistiques globales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-car fa-2x text-primary mb-2"></i>
                        <h3>{{ $vehicles->count() }}</h3>
                        <p class="text-muted mb-0">Véhicules</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-cog fa-2x text-success mb-2"></i>
                        <h3>{{ $totalPieces }}</h3>
                        <p class="text-muted mb-0">Pièces différentes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-boxes fa-2x text-info mb-2"></i>
                        <h3>{{ $totalStock }}</h3>
                        <p class="text-muted mb-0">Stock total</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-2x text-warning mb-2"></i>
                        <h3>{{ $piecesDisponibles }}</h3>
                        <p class="text-muted mb-0">Disponibles</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des véhicules et leurs pièces -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"> Stock de Pièces </h5>
            </div>
            <div class="card-body">
                @if($vehicles->count() > 0)
                    @foreach($vehicles as $vehicle)
                        <div class="card mb-3 border-start border-primary border-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        @if($vehicle->photo_principale)
                                            <img src="{{ asset('storage/' . $vehicle->photo_principale) }}"
                                                 class="img-fluid rounded" style="max-height: 100px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="height: 100px;">
                                                <i class="fas fa-car fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5>{{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->annee }})</h5>
                                                <p class="text-muted mb-2">
                                                    <i class="fas fa-id-card"></i> {{ $vehicle->numero_plaque }} •
                                                    <i class="fas fa-barcode"></i> {{ $vehicle->numero_chassis }} •
                                                    <i class="fas fa-gas-pump"></i> {{ ucfirst($vehicle->carburant) }} •
                                                    <i class="fas fa-tachometer-alt"></i> {{ number_format($vehicle->kilometrage, 0, ',', ' ') }} km
                                                </p>
                                                <span class="badge bg-{{ $vehicle->etat === 'bon' ? 'success' : ($vehicle->etat === 'moyen' ? 'warning' : 'danger') }}">
                                                    État: {{ ucfirst($vehicle->etat) }}
                                                </span>
                                                <span class="badge bg-info ms-2">
                                                    {{ $vehicle->pieces->count() }} pièce(s)
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="text-primary mb-0">{{ number_format($vehicle->prix_epave, 0, ',', ' ') }}FCFA</h4>
                                                <small class="text-muted">Prix du véhicule</small>
                                            </div>
                                        </div>

                                        @if($vehicle->pieces->count() > 0)
                                            <hr>
                                            <h6 class="mb-3">Pièces disponibles :</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
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
                                                    @foreach($vehicle->pieces as $piece)
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
                                                                    <a href="{{ route('pieces.show', $piece) }}"
                                                                       class="btn btn-outline-primary btn-sm" title="Voir">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('pieces.edit', $piece) }}"
                                                                       class="btn btn-outline-secondary btn-sm" title="Modifier">
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
                                            <div class="alert alert-info mt-3">
                                                <i class="fas fa-info-circle"></i> Aucune pièce associée à ce véhicule.
                                                <a href="{{ route('pieces.create') }}?vehicle_id={{ $vehicle->id }}" class="alert-link">
                                                    Ajouter une pièce
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-car fa-4x text-muted mb-3"></i>
                        <h4>Aucun véhicule en stock</h4>
                        <p class="text-muted">Commencez par ajouter une pièce avec un nouveau véhicule</p>
                        <a href="{{ route('pieces.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter une première pièce
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Alertes de stock faible -->
        @php
            $stockFaible = auth()->user()->pieces()->where('quantite', '<=', 3)->where('quantite', '>', 0)->get();
            $stockVide = auth()->user()->pieces()->where('quantite', 0)->get();
        @endphp

        @if($stockFaible->count() > 0 || $stockVide->count() > 0)
            <div class="row mt-4">
                @if($stockFaible->count() > 0)
                    <div class="col-md-6">
                        <div class="card shadow border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Stock faible ({{ $stockFaible->count() }} pièce(s))
                                </h6>
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
                                <h6 class="mb-0">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Stock épuisé ({{ $stockVide->count() }} pièce(s))
                                </h6>
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
@endsection

@section('styles')
    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .border-start {
            border-left-width: 4px !important;
        }
    </style>
@endsection
