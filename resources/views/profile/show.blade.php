@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <!-- Carte profil -->
                <div class="card shadow mb-4">
                    <div class="card-body text-center">
                        @if(auth()->user()->isCasse() && auth()->user()->logo)
                            <img src="{{ asset('storage/' . auth()->user()->logo) }}"
                                 class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif

                        <h4>{{ auth()->user()->name }}</h4>
                        <p class="text-muted">
                            <span class="badge bg-primary">{{ auth()->user()->role }}</span>
                        </p>

                        @if(auth()->user()->isCasse())
                            <p class="mb-1"><strong>{{ auth()->user()->nom_entreprise }}</strong></p>
                            <p class="text-muted small">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ auth()->user()->adresse }}, {{ auth()->user()->code_postal }} {{ auth()->user()->ville }}
                            </p>
                        @endif

                        <p class="text-muted small">
                            <i class="fas fa-envelope"></i> {{ auth()->user()->email }}<br>
                            <i class="fas fa-phone"></i> {{ auth()->user()->telephone ?? 'Non renseigné' }}
                        </p>

                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier le profil
                        </a>
                    </div>
                </div>

                <!-- Statistiques rapides -->
                @if(auth()->user()->isCasse())
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Statistiques</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>Véhicules en stock</span>
                                    <strong>{{ auth()->user()->vehicles()->count() }}</strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>Pièces disponibles</span>
                                    <strong>{{ auth()->user()->pieces()->count() }}</strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span>Commandes ce mois</span>
                                    <strong>{{ auth()->user()->commandes()->whereMonth('created_at', now()->month)->count() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-8">
                <!-- Informations détaillées -->
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold">Informations personnelles</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Nom complet:</strong></div>
                            <div class="col-sm-9">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Email:</strong></div>
                            <div class="col-sm-9">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Téléphone:</strong></div>
                            <div class="col-sm-9">{{ auth()->user()->telephone ?? 'Non renseigné' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Adresse:</strong></div>
                            <div class="col-sm-9">
                                {{ auth()->user()->adresse ?? 'Non renseignée' }}<br>
                                {{ auth()->user()->code_postal ?? '' }} {{ auth()->user()->ville ?? '' }}
                            </div>
                        </div>

                        @if(auth()->user()->isCasse())
                            <hr>
                            <h6 class="font-weight-bold">Informations professionnelles</h6>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Nom de l'entreprise:</strong></div>
                                <div class="col-sm-9">{{ auth()->user()->nom_entreprise }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>SIRET:</strong></div>
                                <div class="col-sm-9">{{ auth()->user()->siret ?? 'Non renseigné' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Description:</strong></div>
                                <div class="col-sm-9">{{ auth()->user()->description ?? 'Non renseignée' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Horaires:</strong></div>
                                <div class="col-sm-9">
                                    @if(auth()->user()->horaires)
                                        @foreach(json_decode(auth()->user()->horaires, true) as $jour => $horaire)
                                            <div>{{ ucfirst($jour) }}: {{ $horaire ?? 'Fermé' }}</div>
                                        @endforeach
                                    @else
                                        Non renseignés
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Changer le mot de passe -->
                <div class="card shadow mt-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold">Changer le mot de passe</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="password_confirmation" class="form-label">Confirmation</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="fas fa-key"></i> Changer le mot de passe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
