@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Tableau de bord </h1>

    <h2>Casses</h2>
    @forelse($casses as $casse)
        <div class="mb-3 p-3 border rounded">
            <strong>{{ $casse->name }}</strong> - {{ $casse->email }}
            @if($casse->vehicles->count() > 0)
                <ul class="mt-2">
                    @foreach($casse->vehicles as $vehicle)
                        <li>{{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->annee }})</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Aucun véhicule enregistré.</p>
            @endif
        </div>
    @empty
        <p class="text-muted">Aucune casse enregistrée.</p>
    @endforelse

    <h2 class="mt-5">Clients</h2>
    @forelse($clients as $client)
        <div class="mb-3 p-3 border rounded">
            <strong>{{ $client->name }}</strong> - {{ $client->email }}
            @if($client->commandes->count() > 0)
                <ul class="mt-2">
                    @foreach($client->commandes as $commande)
                        <li>Commande #{{ $commande->id }} - {{ $commande->statut ?? 'En attente' }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Aucune commande passée.</p>
            @endif
        </div>
    @empty
        <p class="text-muted">Aucun client enregistré.</p>
    @endforelse
</div>
@endsection
