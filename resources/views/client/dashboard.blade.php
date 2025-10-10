@extends('layouts.client')

@section('title', 'Tableau de Bord Client')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tableau de Bord</h1>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['commandes_total'] }}</h4>
                            <p>Commandes Total</p>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['commandes_en_cours'] }}</h4>
                            <p>Commandes en Cours</p>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['panier_items'] }}</h4>
                            <p>Articles Panier</p>
                        </div>
                        <i class="fas fa-cart-shopping fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['notifications_non_lues'] }}</h4>
                            <p>Notifications</p>
                        </div>
                        <i class="fas fa-bell fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Commandes récentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Commandes Récentes</h5>
                </div>
                <div class="card-body">
                    @if($commandesRecentes->count() > 0)
                        <div class="list-group">
                            @foreach($commandesRecentes as $commande)
                                <a href="{{ route('client.detail-commande', $commande) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $commande->numero_commande }}</h6>
                                        <span class="badge bg-{{ $commande->statut === 'livree' ? 'success' : 'warning' }}">
                                        {{ $commande->statut }}
                                    </span>
                                    </div>
                                    <p class="mb-1">Total: {{ number_format($commande->total, 2) }} FCFA</p>
                                    <small>Date: {{ $commande->created_at->format('d/m/Y') }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucune commande récente</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notifications récentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Notifications Récentes</h5>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ is_null($notification->read_at) ? 'list-group-item-warning' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $notification->title }}</h6>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucune notification</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recherche rapide -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recherche Rapide de Pièces</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('client.recherche-pieces') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="marque" class="form-control" placeholder="Marque (ex: Renault)">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="modele" class="form-control" placeholder="Modèle (ex: Clio)">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="categorie" class="form-control" placeholder="Catégorie">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="prix_max" class="form-control" placeholder="Prix max">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
