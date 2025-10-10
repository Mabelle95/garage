{{-- resources/views/casse/ventes-epaves/index.blade.php --}}
@extends('layouts.casse')

@section('title', 'Demandes de Vente d\'Épaves')

@section('content')
<div class="container py-4">
    {{-- Titre --}}
    <div class="mb-4 text-center">
        <h1 class="h2 fw-bold">Demandes de Vente d'Épaves</h1>
        <p class="text-muted">Visualisez les demandes de vente de véhicules de vos clients</p>
    </div>

    {{-- Si aucune demande --}}
    @if($demandes->isEmpty())
        <div class="alert alert-warning text-center">
            <i class="fas fa-car-crash fa-2x mb-2"></i>
            <h5 class="mb-1">Aucune demande de vente d'épave</h5>
            <small>Les demandes de vos clients apparaîtront ici.</small>
        </div>
    @else
        {{-- Tableau --}}
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Véhicule</th>
                        <th>Client</th>
                        <th>Prix souhaité</th>
                        <th>État</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demandes as $demande)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $demande->marque }} {{ $demande->modele }}</strong><br>
                                <small class="text-muted">{{ $demande->annee }} - {{ $demande->immatriculation }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                {{ $demande->user->name }}<br>
                                <small class="text-muted">{{ $demande->user->email }}</small>
                            </div>
                        </td>
                        <td>
                            @if($demande->prix_souhaite)
                                {{ number_format($demande->prix_souhaite, 2) }} FCFA
                            @else
                                <span class="text-muted">Non spécifié</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = match($demande->statut) {
                                    'acceptee' => 'bg-success text-white',
                                    'refusee' => 'bg-danger text-white',
                                    'evaluee' => 'bg-primary text-white',
                                    default => 'bg-warning text-dark',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ str_replace('_', ' ', ucfirst($demande->statut)) }}
                            </span>
                        </td>
                        <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('gestion.epaves.show', $demande) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
