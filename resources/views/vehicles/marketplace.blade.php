@extends('layouts.app')

@section('title', 'Véhicules d\'occasion - Marketplace')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Véhicules d'occasion disponibles</h1>
            <div class="text-muted">
                {{ $vehicles->total() }} véhicule(s) trouvé(s)
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par marque, modèle..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="marque" class="form-select">
                            <option value="">Toutes les marques</option>
                            @foreach($marques as $marque)
                                <option value="{{ $marque }}" {{ request('marque') == $marque ? 'selected' : '' }}>{{ $marque }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="prix_max" class="form-control" placeholder="Prix maximum..." value="{{ request('prix_max') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </form>

                <!-- Filtres de géolocalisation -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="useLocation" onclick="toggleLocationFilter()">
                            <label class="form-check-label" for="useLocation">
                                <i class="fas fa-map-marker-alt"></i> Filtrer par proximité
                            </label>
                        </div>
                    </div>
                </div>

                <div id="locationFilter" class="row mt-2" style="display: none;">
                    <div class="col-md-4">
                        <input type="number" step="any" class="form-control" id="latitude" placeholder="Latitude" value="{{ request('latitude') }}">
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="any" class="form-control" id="longitude" placeholder="Longitude" value="{{ request('longitude') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="radius">
                            <option value="10">10 km</option>
                            <option value="25" selected>25 km</option>
                            <option value="50">50 km</option>
                            <option value="100">100 km</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="getCurrentLocation()" title="Utiliser ma position">
                            <i class="fas fa-location-arrow"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Résultats -->
        @if($vehicles->count() > 0)
            <div class="row">
                @foreach($vehicles as $vehicle)
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="card vehicle-card shadow h-100">
                            <!-- Image du véhicule -->
                            @if($vehicle->photo_principale)
                                <img src="{{ asset('storage/' . $vehicle->photo_principale) }}"
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;"
                                     alt="{{ $vehicle->marque }} {{ $vehicle->modele }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="fas fa-car fa-3x text-muted"></i>
                                </div>
                            @endif

                            <!-- Badge statut -->
                            <div class="card-img-overlay">
                                @if($vehicle->vendu)
                                    <span class="badge bg-danger">Vendu</span>
                                @else
                                    <span class="badge bg-success">Disponible</span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <!-- En-tête -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title">{{ $vehicle->marque }} {{ $vehicle->modele }}</h5>
                                    <span class="badge bg-primary">{{ $vehicle->annee }}</span>
                                </div>

                                <!-- Informations techniques -->
                                <div class="vehicle-info mb-3">
                                    <div class="row small text-muted">
                                        <div class="col-6">
                                            <i class="fas fa-gas-pump"></i> {{ ucfirst($vehicle->carburant) }}
                                        </div>
                                        <div class="col-6">
                                            <i class="fas fa-tachometer-alt"></i> {{ number_format($vehicle->kilometrage, 0, ',', ' ') }} km
                                        </div>
                                        <div class="col-6">
                                            <i class="fas fa-cog"></i> {{ ucfirst($vehicle->transmission) }}
                                        </div>
                                        <div class="col-6">
                                            <i class="fas fa-palette"></i> {{ $vehicle->couleur }}
                                        </div>
                                    </div>
                                </div>

                                <!-- État du véhicule -->
                                <div class="mb-3">
                            <span class="badge bg-{{ $vehicle->etat === 'bon' ? 'success' : ($vehicle->etat === 'moyen' ? 'warning' : 'danger') }}">
                                État: {{ ucfirst($vehicle->etat) }}
                            </span>
                                </div>

                                <!-- Casse propriétaire -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center">
                                        @if($vehicle->casse->logo)
                                            <img src="{{ asset('storage/' . $vehicle->casse->logo) }}"
                                                 class="rounded me-2"
                                                 width="30" height="30"
                                                 style="object-fit: cover;"
                                                 alt="{{ $vehicle->casse->nom_entreprise }}">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 30px; height: 30px;">
                                                <i class="fas fa-warehouse text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <small class="text-muted">Vendu par</small>
                                            <div class="fw-bold">{{ $vehicle->casse->nom_entreprise }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i> {{ $vehicle->casse->ville }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Prix et actions -->
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="text-primary mb-0">{{ number_format($vehicle->prix_epave, 0, ',', ' ') }} FCFA</h4>
                                            <small class="text-muted">Prix de l'épave</small>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            <button class="btn btn-outline-secondary"
                                                    onclick="showContactInfo({{ $vehicle->id }})"
                                                    title="Contacter la casse">
                                                <i class="fas fa-phone"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $vehicles->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-car fa-4x text-muted mb-3"></i>
                <h4>Aucun véhicule trouvé</h4>
                <p class="text-muted">Aucun véhicule ne correspond à vos critères de recherche.</p>
                <a href="{{ route('vehicles.index') }}" class="btn btn-primary">
                    <i class="fas fa-refresh"></i> Réinitialiser les filtres
                </a>
            </div>
        @endif
    </div>

    <!-- Modal pour les informations de contact -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contacter la casse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="contactInfo">
                        <!-- Les informations de contact seront chargées ici -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Géolocalisation
        function toggleLocationFilter() {
            const locationFilter = document.getElementById('locationFilter');
            const useLocation = document.getElementById('useLocation');

            if (useLocation.checked) {
                locationFilter.style.display = 'block';
                getCurrentLocation();
            } else {
                locationFilter.style.display = 'none';
                // Retirer les paramètres de localisation de l'URL
                const url = new URL(window.location.href);
                url.searchParams.delete('latitude');
                url.searchParams.delete('longitude');
                url.searchParams.delete('radius');
                window.location.href = url.toString();
            }
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                        // Appliquer le filtre automatiquement
                        applyLocationFilter();
                    },
                    function(error) {
                        console.error('Erreur de géolocalisation:', error);
                        alert('Impossible d\'obtenir votre position. Veuillez saisir manuellement les coordonnées.');
                    }
                );
            } else {
                alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            }
        }

        function applyLocationFilter() {
            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;
            const radius = document.getElementById('radius').value;

            if (latitude && longitude) {
                const url = new URL(window.location.href);
                url.searchParams.set('latitude', latitude);
                url.searchParams.set('longitude', longitude);
                url.searchParams.set('radius', radius);
                window.location.href = url.toString();
            }
        }

        // Afficher les informations de contact
        function showContactInfo(vehicleId) {
            // Simuler un chargement des données de contact
            // En réalité, vous feriez une requête AJAX vers votre API
            const contactInfo = `
        <div class="text-center">
            <h6>Contacter le vendeur</h6>
            <p><i class="fas fa-phone text-primary"></i> <strong>01 23 45 67 89</strong></p>
            <p><i class="fas fa-envelope text-primary"></i> contact@casse-auto.com</p>
            <p><i class="fas fa-map-marker-alt text-primary"></i> 123 Rue de l'Automobile, 75000 Paris</p>
            <p class="text-muted small">N'hésitez pas à nous contacter pour plus d'informations sur ce véhicule.</p>
        </div>
    `;

            document.getElementById('contactInfo').innerHTML = contactInfo;
            new bootstrap.Modal(document.getElementById('contactModal')).show();
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si des paramètres de localisation sont présents
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('latitude') || urlParams.has('longitude')) {
                document.getElementById('useLocation').checked = true;
                document.getElementById('locationFilter').style.display = 'block';
            }

            // Mettre à jour le radius si présent dans l'URL
            if (urlParams.has('radius')) {
                document.getElementById('radius').value = urlParams.get('radius');
            }

            // Écouter les changements de radius
            document.getElementById('radius').addEventListener('change', function() {
                if (document.getElementById('useLocation').checked) {
                    applyLocationFilter();
                }
            });
        });
    </script>

    @section('styles')
        <style>
            .vehicle-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: none;
                border-radius: 10px;
            }

            .vehicle-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
            }

            .vehicle-info {
                border-top: 1px solid #e9ecef;
                border-bottom: 1px solid #e9ecef;
                padding: 10px 0;
            }

            .card-img-overlay .badge {
                font-size: 0.8rem;
                padding: 5px 10px;
            }

            .vehicle-card .btn {
                border-radius: 20px;
                padding: 5px 15px;
                font-size: 0.9rem;
            }

            .vehicle-card .card-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #2c3e50;
            }

            .vehicle-card .text-primary {
                color: #3498db !important;
            }

            .badge {
                font-weight: 500;
            }

            /* Style pour la pagination */
            .pagination .page-link {
                color: #3498db;
                border: 1px solid #dee2e6;
            }

            .pagination .page-item.active .page-link {
                background-color: #3498db;
                border-color: #3498db;
            }

            /* Style pour les filtres */
            #locationFilter {
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                border: 1px solid #e9ecef;
            }

            .form-check-label {
                font-weight: 500;
                color: #2c3e50;
            }
        </style>
    @endsection
