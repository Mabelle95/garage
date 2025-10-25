@extends('layouts.app')

@section('title', $vehicle->marque . ' ' . $vehicle->modele)

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->annee }})</h1>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Galerie photos -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @if($vehicle->photo_principale || $vehicle->photos_additionnelles)
                            <div class="row">
                                @if($vehicle->photo_principale)
                                    <div class="col-md-6 mb-3">
                                        <img src="{{ asset('storage/' . $vehicle->photo_principale) }}"
                                             class="img-fluid rounded"
                                             style="cursor: pointer"
                                             onclick="openModal('{{ asset('storage/' . $vehicle->photo_principale) }}')">
                                    </div>
                                @endif
                                @if($vehicle->photos_additionnelles)
                                    @foreach($vehicle->photos_additionnelles as $photo)
                                        <div class="col-md-3 mb-3">
                                            <img src="{{ asset('storage/' . $photo) }}"
                                                 class="img-fluid rounded"
                                                 style="cursor: pointer"
                                                 onclick="openModal('{{ asset('storage/' . $photo) }}')">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-car fa-3x text-muted"></i>
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
                                        <th width="40%">Marque:</th>
                                        <td>{{ $vehicle->marque }}</td>
                                    </tr>
                                    <tr>
                                        <th>Modèle:</th>
                                        <td>{{ $vehicle->modele }}</td>
                                    </tr>
                                    <tr>
                                        <th>Année:</th>
                                        <td>{{ $vehicle->annee }}</td>
                                    </tr>
                                    <tr>
                                        <th>Carburant:</th>
                                        <td>{{ ucfirst($vehicle->carburant) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Transmission:</th>
                                        <td>{{ ucfirst($vehicle->transmission) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Kilométrage:</th>
                                        <td>{{ number_format($vehicle->kilometrage, 0, ',', ' ') }} km</td>
                                    </tr>
                                    <tr>
                                        <th>État:</th>
                                        <td>
                                        <span class="badge bg-{{ $vehicle->etat === 'bon' ? 'success' : ($vehicle->etat === 'moyen' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($vehicle->etat) }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Prix:</th>
                                        <td class="h5 text-primary">{{ number_format($vehicle->prix_epave, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <th>Numéro chassis:</th>
                                        <td>{{ $vehicle->numero_chassis }}</td>
                                    </tr>
                                    <tr>
                                        <th>Numéro plaque:</th>
                                        <td>{{ $vehicle->numero_plaque }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($vehicle->description)
                            <div class="mt-3">
                                <h6>Description:</h6>
                                <p class="text-muted">{{ $vehicle->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pièces disponibles -->
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Pièces disponibles</h5>
                        <span class="badge bg-primary">{{ $vehicle->pieces->count() }} pièce(s)</span>
                    </div>
                    <div class="card-body">
                        @if($vehicle->pieces->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prix</th>
                                        <th>Quantité</th>
                                        <th>État</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vehicle->pieces as $piece)
                                        <tr>
                                            <td>
                                                <strong>{{ $piece->nom }}</strong>
                                                @if($piece->reference_constructeur)
                                                    <br><small class="text-muted">{{ $piece->reference_constructeur }}</small>
                                                @endif
                                            </td>
                                            <td>{{ number_format($piece->prix, 2, ',', ' ') }} FCFA</td>
                                            <td>
                                            <span class="badge bg-{{ $piece->quantite > 0 ? 'success' : 'danger' }}">
                                                {{ $piece->quantite }}
                                            </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($piece->etat) }}</span>
                                            </td>
                                            <td>
                                                @if($piece->disponible && $piece->quantite > 0)
                                                    <a href="{{ route('pieces.show', $piece) }}" class="btn btn-sm btn-outline-primary">
                                                        Voir
                                                    </a>
                                                @else
                                                    <span class="text-muted">Indisponible</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-cog fa-3x text-muted"></i>
                                <p class="text-muted mt-2">Aucune pièce disponible pour ce véhicule</p>
                                @if(auth()->user()->isCasse() && auth()->user()->id === $vehicle->casse_id)
                                    <a href="{{ route('pieces.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Ajouter une pièce
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Informations casse -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0">Informations de la casse</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            @if($vehicle->casse->logo)
                                <img src="{{ asset('storage/' . $vehicle->casse->logo) }}"
                                     class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-warehouse fa-2x text-white"></i>
                                </div>
                            @endif
                            <h6>{{ $vehicle->casse->nom_entreprise }}</h6>
                            <p class="text-muted small">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $vehicle->casse->adresse }}, {{ $vehicle->casse->ville }}<br>
                                <i class="fas fa-phone"></i> {{ $vehicle->casse->telephone }}
                            </p>
                            <p class="small">{{ Str::limit($vehicle->casse->description, 100) }}</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Voir la casse</a>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if(auth()->user()->isCasse() && auth()->user()->id === $vehicle->casse_id)
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0">Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i> Modifier le véhicule
                                </a>
                                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-grid">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('Supprimer ce véhicule ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Véhicules similaires -->
                @if($vehiclesSimilaires->count() > 0)
                    <div class="card shadow mt-4">
                        <div class="card-header">
                            <h6 class="m-0">Véhicules similaires</h6>
                        </div>
                        <div class="card-body">
                            @foreach($vehiclesSimilaires as $similaire)
                                <div class="d-flex mb-3">
                                    @if($similaire->photo_principale)
                                        <img src="{{ asset('storage/' . $similaire->photo_principale) }}"
                                             class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-car text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $similaire->marque }} {{ $similaire->modele }}</h6>
                                        <small class="text-muted">{{ $similaire->annee }} • {{ number_format($similaire->prix_epave, 0, ',', ' ') }} FCFA</small><br>
                                        <a href="{{ route('vehicles.show', $similaire) }}" class="btn btn-sm btn-outline-primary mt-1">Voir</a>
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
