@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Tableau de bord Admin</h1>

    {{-- Section Casses --}}
    <h3 class="mb-3">Casses</h3>
    <div class="row mb-5">
        @forelse($casses as $casse)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                        <span class="fw-bold">{{ $casse->name }}</span>
                        <form action="{{ route('admin.users.destroy', $casse->id) }}" method="POST" onsubmit="return confirm('Supprimer cette casse ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <p><strong>Email:</strong> {{ $casse->email }}</p>
                        <p><strong>Véhicules:</strong> {{ $casse->vehicles->count() }}</p>
                        <p><strong>Pièces:</strong> {{ $casse->pieces->count() }}</p>
                        @if($casse->pieces->count())
                            <ul class="list-unstyled">
                                @foreach($casse->pieces as $piece)
                                    <li class="mb-1 d-flex justify-content-between">
                                        <span>{{ $piece->nom ?? 'Inconnue' }} (Qté: {{ $piece->quantite ?? 0 }})</span>
                                        @if($piece->disponible ?? false)
                                            <span class="badge text-dark" style="background-color: #a3d9a5;">Disponible</span>
                                        @else
                                            <span class="badge text-dark" style="background-color: #e0e0e0;">Indisponible</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucune casse trouvée.</p>
        @endforelse
    </div>

    {{-- Section Clients --}}
    <h3 class="mb-3">Clients</h3>
    <div class="row">
        @forelse($clients as $client)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                        <span class="fw-bold">{{ $client->name }}</span>
                        <form action="{{ route('admin.users.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Supprimer ce client ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <p><strong>Email:</strong> {{ $client->email }}</p>
                        <p><strong>Commandes:</strong> {{ $client->commandes->count() }}</p>
                        @if($client->commandes->count())
                            <ul class="list-unstyled">
                                @foreach($client->commandes as $commande)
                                    <li class="mb-2 p-2 rounded" style="background-color: #f5f5f5;">
                                        <strong>#{{ $commande->numero_commande ?? 'Inconnu' }}</strong>
                                        <span class="badge text-dark" style="background-color: #cce5ff;">{{ $commande->statut ?? 'Inconnu' }}</span>
                                        @if($commande->items->count())
                                            <ul class="list-unstyled mt-1">
                                                @foreach($commande->items as $item)
                                                    <li>
                                                        {{ $item->nom_piece ?? 'Pièce inconnue' }}
                                                        (Casse: {{ $item->nom_casse ?? 'Inconnue' }}) -
                                                        Qté: {{ $item->quantite ?? 0 }} -
                                                        Prix: {{ $item->prix_unitaire ?? 0 }} FCFA
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucun client trouvé.</p>
        @endforelse
    </div>
</div>
@endsection
