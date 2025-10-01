@extends('layouts.app')

@section('title', $piece->nom)

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $piece->nom }}</h1>
            <a href="{{ route('pieces.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Galerie photos -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if($piece->photos && count($piece->photos) > 0)
                            <div class="row">
                                @foreach($piece->photos as $photo)
                                    <div class="col-md-3 mb-3">
                                        <img src="{{ asset('storage/' . $photo) }}"
                                             class="img-fluid rounded"
                                             style="cursor: pointer"
                                             onclick="openModal('{{ asset('storage/' . $photo) }}')">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-cog fa-3x text-muted"></i>
                                <p class="text-muted mt-2">Aucune photo disponible</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations techniques -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="m-0">Informations techniques</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Nom:</th>
                                        <td>{{ $piece->nom }}</td>
                                    </tr>
                                    <tr>
                                        <th>Référence:</th>
                                        <td>{{ $piece->reference_constructeur ?? 'Non spécifiée' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Prix:</th>
                                        <td class="h5 text-primary">{{ number_format($piece->prix, 2, ',', ' ') }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <th>Quantité:</th>
                                        <td>
                                        <span class="badge bg-{{ $piece->quantite > 10 ? 'success' : ($piece->quantite > 0 ? 'warning' : 'danger') }}">
                                            {{ $piece->quantite }}
                                        </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">État:</th>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $piece->etat)) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Disponible:</th>
                                        <td>
                                        <span class="badge bg-{{ $piece->disponible ? 'success' : 'danger' }}">
                                            {{ $piece->disponible ? 'Oui' : 'Non' }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Véhicule:</th>
                                        <td>{{ $piece->vehicle->marque }} {{ $piece->vehicle->modele }} ({{ $piece->vehicle->annee }})</td>
                                    </tr>
                                    <tr>
                                        <th>Compatible avec:</th>
                                        <td>
                                            @if(!empty($piece->compatible_avec))
                                                {{ $piece->compatible_avec }}
                                            @else
                                                Non spécifié
                                            @endif

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($piece->description)
                            <div class="mt-3">
                                <h6>Description:</h6>
                                <p class="text-muted">{{ $piece->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pièces similaires -->
                @if($piecesSimilaires->count() > 0)
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="m-0">Pièces similaires</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($piecesSimilaires as $similaire)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>{{ $similaire->nom }}</h6>
                                                <p class="text-muted small">
                                                    {{ $similaire->vehicle->marque }} {{ $similaire->vehicle->modele }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                <span class="h6 text-primary mb-0">
                                                    {{ number_format($similaire->prix, 2, ',', ' ') }} FCFA
                                                </span>
                                                    <a href="{{ route('pieces.show', $similaire) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Informations casse -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0">Informations de la casse</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            @if($piece->vehicle->casse->logo)
                                <img src="{{ asset('storage/' . $piece->vehicle->casse->logo) }}"
                                     class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-warehouse fa-2x text-white"></i>
                                </div>
                            @endif
                            <h6>{{ $piece->vehicle->casse->nom_entreprise }}</h6>
                            <p class="text-muted small">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $piece->vehicle->casse->adresse }}, {{ $piece->vehicle->casse->ville }}<br>
                                <i class="fas fa-phone"></i> {{ $piece->vehicle->casse->telephone }}
                            </p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Voir la casse</a>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if(auth()->user()->isCasse() && auth()->user()->id === $piece->vehicle->casse_id)
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h6 class="m-0">Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('pieces.edit', $piece) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i> Modifier la pièce
                                </a>
                                <form action="{{ route('pieces.destroy', $piece) }}" method="POST" class="d-grid">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('Supprimer cette pièce ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Ajouter au panier -->
                @if(auth()->user()->isClient() && $piece->disponible && $piece->quantite > 0)
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0">Acheter cette pièce</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('panier.add', $piece) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="quantite" class="form-label">Quantité</label>
                                    <input type="number" class="form-control" id="quantite" name="quantite"
                                           value="1" min="1" max="{{ $piece->quantite }}">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus"></i> Ajouter au panier
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif(auth()->user()->isClient())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Cette pièce n'est pas disponible pour le moment.
                    </div>
                @endif

                <!-- Autres pièces du véhicule -->
                @if($autresPiecesVehicule->count() > 0)
                    <div class="card shadow mt-4">
                        <div class="card-header">
                            <h6 class="m-0">Autres pièces de ce véhicule</h6>
                        </div>
                        <div class="card-body">
                            @foreach($autresPiecesVehicule as $autrePiece)
                                <div class="d-flex mb-3">
                                    @if($autrePiece->photos && count($autrePiece->photos) > 0)
                                        <img src="{{ asset('storage/' . $autrePiece->photos[0]) }}"
                                             class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-cog text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $autrePiece->nom }}</h6>
                                        <small class="text-muted">{{ number_format($autrePiece->prix, 2, ',', ' ') }} FCFA</small><br>
                                        <a href="{{ route('pieces.show', $autrePiece) }}" class="btn btn-sm btn-outline-primary mt-1">Voir</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
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
