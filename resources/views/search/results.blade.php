@extends('layouts.app')

@section('title', 'Résultats de recherche')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Résultats de recherche</h1>
            <div class="text-muted">"{{ $query }}"</div>
        </div>

        <!-- Formulaire de filtrage -->
        <form method="GET" action="{{ route('search') }}" class="mb-4 d-flex flex-wrap gap-2 align-items-center">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="text" name="q" value="{{ $query }}" placeholder="Rechercher..." class="form-control flex-grow-1">

            <select name="ville" class="form-select" style="min-width: 180px;">
                <option value="">Toutes les villes</option>
                @foreach(['Lomé','Sokodé','Kara','Atakpamé'] as $v)
                    <option value="{{ $v }}" {{ request('ville') === $v ? 'selected' : '' }}>
                        {{ $v }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>

        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ $type === 'all' ? 'active' : '' }}"
                   href="{{ route('search', ['q' => $query, 'type' => 'all', 'ville' => request('ville')]) }}">
                    Tous ({{ ($results['pieces']->count() ?? 0) + ($results['vehicles']->count() ?? 0) + ($results['casses']->count() ?? 0) }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'pieces' ? 'active' : '' }}"
                   href="{{ route('search', ['q' => $query, 'type' => 'pieces', 'ville' => request('ville')]) }}">
                    Pièces ({{ $results['pieces']->count() ?? 0 }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'vehicles' ? 'active' : '' }}"
                   href="{{ route('search', ['q' => $query, 'type' => 'vehicles', 'ville' => request('ville')]) }}">
                    Véhicules ({{ $results['vehicles']->count() ?? 0 }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'casses' ? 'active' : '' }}"
                   href="{{ route('search', ['q' => $query, 'type' => 'casses', 'ville' => request('ville')]) }}">
                    Casses ({{ $results['casses']->count() ?? 0 }})
                </a>
            </li>
        </ul>

        <!-- Résultats Pièces -->
        @if($type === 'all' || $type === 'pieces')
            @if(isset($results['pieces']) && $results['pieces']->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fas fa-cog me-2"></i>Pièces détachées</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($results['pieces'] as $piece)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        @if($piece->photos && count($piece->photos) > 0)
                                            <img src="{{ asset('storage/' . $piece->photos[0]) }}"
                                                 class="card-img-top" style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                 style="height: 200px;">
                                                <i class="fas fa-cog fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $piece->nom }}</h6>
                                            <p class="card-text small text-muted">
                                                {{ $piece->vehicle->marque }} {{ $piece->vehicle->modele }} •
                                                {{ $piece->vehicle->annee }} • {{ $piece->vehicle->casse->ville }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h6 text-primary mb-0">
                                                    {{ number_format($piece->prix, 2, ',', ' ') }} FCFA
                                                </span>
                                                <a href="{{ route('pieces.show', $piece) }}" class="btn btn-sm btn-primary">
                                                    Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Résultats Véhicules -->
        @if($type === 'all' || $type === 'vehicles')
            @if(isset($results['vehicles']) && $results['vehicles']->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fas fa-car me-2"></i>Véhicules</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($results['vehicles'] as $vehicle)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        @if($vehicle->photo_principale)
                                            <img src="{{ asset('storage/' . $vehicle->photo_principale) }}"
                                                 class="card-img-top" style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                 style="height: 200px;">
                                                <i class="fas fa-car fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $vehicle->marque }} {{ $vehicle->modele }}</h6>
                                            <p class="card-text small text-muted">
                                                {{ $vehicle->annee }} • {{ $vehicle->carburant }} • {{ number_format($vehicle->kilometrage, 0, ',', ' ') }} km • {{ $vehicle->casse->ville }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h6 text-primary mb-0">
                                                    {{ number_format($vehicle->prix_epave, 0, ',', ' ') }} FCFA
                                                </span>
                                                <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-sm btn-primary">
                                                    Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Résultats Casses -->
        @if($type === 'all' || $type === 'casses')
            @if(isset($results['casses']) && $results['casses']->count() > 0)
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fas fa-warehouse me-2"></i>Casses</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($results['casses'] as $casse)
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                @if($casse->logo)
                                                    <img src="{{ asset('storage/' . $casse->logo) }}"
                                                         class="rounded me-3" width="80" height="80" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 80px; height: 80px;">
                                                        <i class="fas fa-warehouse fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title">{{ $casse->nom_entreprise }}</h6>
                                                    <p class="card-text small text-muted">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        {{ $casse->adresse }}, {{ $casse->ville }}<br>
                                                        <i class="fas fa-phone"></i> {{ $casse->telephone }}
                                                    </p>
                                                    <p class="card-text small">{{ Str::limit($casse->description, 100) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Aucun résultat -->
        @if(($type === 'all' && (!isset($results['pieces']) || $results['pieces']->count() === 0) &&
            (!isset($results['vehicles']) || $results['vehicles']->count() === 0) &&
            (!isset($results['casses']) || $results['casses']->count() === 0)) ||
            ($type === 'pieces' && (!isset($results['pieces']) || $results['pieces']->count() === 0)) ||
            ($type === 'vehicles' && (!isset($results['vehicles']) || $results['vehicles']->count() === 0)) ||
            ($type === 'casses' && (!isset($results['casses']) || $results['casses']->count() === 0)))
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h4>Aucun résultat trouvé</h4>
                <p class="text-muted">Essayez avec d'autres termes de recherche ou une autre ville</p>
                <a href="{{ route('pieces.index') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Parcourir toutes les pièces
                </a>
            </div>
        @endif
    </div>
@endsection
