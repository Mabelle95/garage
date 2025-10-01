@extends('layouts.app') {{-- ou ton layout admin si différent --}}

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Tableau de bord</h1>

    {{-- Section Casses --}}
    <h3>Casses</h3>
    <div class="row mb-5">
        @forelse($casses as $casse)
            <div class="col-md-6 mb-3">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        {{ $casse->name }} ({{ $casse->email }})
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre de véhicules:</strong> {{ $casse->vehicles->count() }}</p>
                        <p><strong>Nombre de pièces:</strong> {{ $casse->pieces->count() }}</p>
                        <ul>
                            @foreach($casse->pieces as $piece)
                                <li>
                                    {{ $piece->nom ?? 'Inconnue' }} -
                                    Qté: {{ $piece->quantite ?? 0 }} -
                                    @if($piece->disponible ?? false)
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-secondary">Indisponible</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <p>Aucune casse trouvée.</p>
        @endforelse
    </div>

    {{-- Section Clients --}}
    <h3>Clients</h3>
    <div class="row">
        @forelse($clients as $client)
            <div class="col-md-6 mb-3">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        {{ $client->name }} ({{ $client->email }})
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre de commandes:</strong> {{ $client->commandes->count() }}</p>
                        <ul>
                            @foreach($client->commandes as $commande)
                                <li>
                                    <strong>Commande #{{ $commande->numero_commande ?? 'Inconnue' }}</strong> - Statut: {{ $commande->statut ?? 'Inconnu' }}
                                    <ul>
                                        @foreach($commande->items as $item)
                                            <li>
                                                Pièce: {{ $item->nom_piece }}
                                                (Casse: {{ $item->nom_casse }}) -
                                                Qté: {{ $item->quantite ?? 0 }} -
                                                Prix: {{ $item->prix_unitaire ?? 0 }}FCFA
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <p>Aucun client trouvé.</p>
        @endforelse
    </div>
</div>
@endsection
