@extends('layouts.app')

@section('title', 'Mon panier')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Mon panier</h1>
            @if (count($panierParCasse) > 0)
                {{-- <div>
                    <span class="h4 text-primary me-3">Total: {{ number_format($panierParCasse->getTotal(), 2, ',', ' ') }}
                        FCFA</span>
                    <a href="{{ route('commandes.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-credit-card"></i> Passer la commande
                    </a>
                </div> --}}
            @endif
        </div>

        @if (count($panierParCasse) > 0)
            @foreach ($panierParCasse as $casse)
                {{-- @foreach ($casse as $casseItem) --}}
                {{-- @dd($casseItem) --}}
                    <div class="card shadow">
                        {{-- bool here --}}
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Piece</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($casse as $casseItem)
                                        {{-- row --}}
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($casseItem['piece'] && $casseItem['piece']['photos'] && count($casseItem['piece']['photos']) > 0)
                                                        <img src="{{ asset('storage/' . $casseItem['piece']['photos'][0]) }}"
                                                            class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 60px; height: 60px;">
                                                            <i class="fas fa-cog text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $casseItem['piece']['nom'] ?? 'N/A' }}</strong><br>
                                                        <small class="text-muted">
                                                            @if ($casseItem['piece'] && $casseItem['piece']['vehicle'])
                                                                {{ $casseItem['piece']['vehicle']['marque'] ?? '' }}
                                                                {{ $casseItem['piece']['vehicle']['modele'] ?? '' }} â€¢
                                                                {{ $casseItem['piece']['vehicle']['casse']['nom_entreprise'] ?? '' }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- prix article --}}
                                            <td>{{ number_format($casseItem['piece']['prix'] ?? 0, 2, ',', ' ') }} FCFA</td>

                                            {{-- quantite article --}}
                                            <td>
                                                <form action="{{ route('panier.update', $casseItem['id']) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group" style="width: 120px;">
                                                        <input type="number" name="quantite" class="form-control"
                                                            value="{{ $casseItem['quantite'] ?? 1 }}" min="1"
                                                            max="{{ $casseItem['piece']['quantite'] ?? 999 }}">
                                                        <button type="submit" class="btn btn-outline-primary">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>{{ number_format(($casseItem['quantite'] ?? 0) * ($casseItem['piece']['prix'] ?? 0), 2, ',', ' ') }}
                                                FCFA</td>
                                            <td>
                                                <form action="{{ route('panier.remove', $casseItem['id']) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        {{-- .row --}}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <form action="{{ route('panier.clear') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('Vider tout le panier ?')">
                                        <i class="fas fa-trash"></i> Vider le panier
                                    </button>
                                </form>

                                <div class="text-end">
                                    {{-- @dd($totalParCmd) --}}
                                    {{-- @dd($casse[0]['piece']['user_id']) --}}
                                    @php
                                    $casseUserId = $casse[0]['piece']['user_id'] ?? null;
                                    @endphp
                                    <h4>Sous-total: {{ number_format($totalParCmd[$casseUserId], 2, ',', ' ') }} FCFA</h4>
                                    {{-- <h4>Sous-total: {{ number_format(array_sum($totalParCmd[$casseUserId] ?? 0), 2, ',', ' ') }} FCFA</h4> --}}
                                    <small class="text-muted">Frais de livraison calculer à l'etape suivante</small><br>
                                    <a href="{{ route('commandes.create', $casseUserId) }}" class="btn btn-primary btn-lg mt-2">
                                        <i class="fas fa-credit-card"></i> Commander maintenant
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <hr>
            @endforeach


        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4>Votre panier est vide</h4>
                <p class="text-muted">Ajoutez des pieces d'étaché à votre panier</p>
                <a href="{{ route('pieces.index') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Parcourir les pieces
                </a>
            </div>
        @endif
    </div>
@endsection