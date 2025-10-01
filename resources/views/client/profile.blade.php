@extends('layouts.client')

@section('title', 'Mon Profil')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mon Profil</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Informations du compte -->
        <div class="card">
            <div class="card-header">
                <h5>Informations du compte</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-4x text-muted"></i>
                </div>
                <h5>{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->email }}</p>
                <p class="text-muted">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>

                <div class="mt-4">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditProfile">
                            <i class="fas fa-edit"></i> Modifier le profil
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="card mt-4">
            <div class="card-header">
                <h6>Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Commandes totales:</span>
                    <strong>{{ $user->commandes()->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Favoris:</span>
                    <strong>{{ $user->favoris()->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Épaves proposées:</span>
                    <strong>{{ $user->venteEpaves()->count() }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Dernières activités -->
        <div class="card">
            <div class="card-header">
                <h5>Activités récentes</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @php
                        $activites = [
                            'commandes' => $user->commandes()->latest()->limit(3)->get(),
                            'favoris' => $user->favoris()->latest()->limit(2)->get(),
                            'ventes' => $user->venteEpaves()->latest()->limit(2)->get()
                        ];
                    @endphp

                    <!-- Commandes récentes -->
                    @foreach($activites['commandes'] as $commande)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <i class="fas fa-shopping-cart text-primary me-2"></i>
                                    <strong>Commande #{{ $commande->numero_commande }}</strong>
                                    <span class="badge bg-{{ [
                                        'en_attente' => 'warning',
                                        'confirmee' => 'info',
                                        'livree' => 'success'
                                    ][$commande->statut] }} ms-2">{{ $commande->statut }}</span>
                                </div>
                                <small class="text-muted">{{ $commande->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">Total: {{ number_format($commande->total, 0, ',', ' ') }} FCFA</p>
                        </div>
                    @endforeach

                    <!-- Favoris récents -->
                    @foreach($activites['favoris'] as $favori)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <i class="fas fa-heart text-danger me-2"></i>
                                    <strong>Ajout aux favoris</strong>
                                </div>
                                <small class="text-muted">{{ $favori->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $favori->piece->nom }}</p>
                        </div>
                    @endforeach

                    <!-- Ventes d'épaves récentes -->
                    @foreach($activites['ventes'] as $vente)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <i class="fas fa-car-crash text-warning me-2"></i>
                                    <strong>Épave proposée</strong>
                                </div>
                                <small class="text-muted">{{ $vente->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">Prix: {{ number_format($vente->prix, 0, ',', ' ') }} FCFA</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
