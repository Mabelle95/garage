@extends('layouts.client')

@section('title', 'Mes Commandes')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mes Commandes</h1>
    </div>

    <div class="card">
        <div class="card-body">
            @if($commandes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Date</th>
                            <th>Casse</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($commandes as $commande)
                            <tr>
                                <td>
                                    <strong>{{ $commande->numero_commande }}</strong>
                                </td>
                                <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $commande->casse->name }}</td>
                                <td>{{ number_format($commande->total, 2) }} FCFA</td>
                                <td>
                                    <span class="badge bg-{{ [
                                        'en_attente' => 'warning',
                                        'confirmee' => 'info',
                                        'preparation' => 'primary',
                                        'expediee' => 'secondary',
                                        'livree' => 'success',
                                        'annulee' => 'danger'
                                    ][$commande->statut] ?? 'secondary' }}">
                                        {{ str_replace('_', ' ', $commande->statut) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('client.detail-commande', $commande) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $commandes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                    <h4>Aucune commande</h4>
                    <p class="text-muted">Vous n'avez pas encore passé de commande</p>
                    <a href="{{ route('client.recherche-pieces') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Rechercher des pièces
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
