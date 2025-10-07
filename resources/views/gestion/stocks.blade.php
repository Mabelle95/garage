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
        </div>

        <!-- Liste des pièces -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Stock de Pièces</h5>
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
@endsection

@section('styles')
    <style>
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
@endsection
