@extends('layouts.app')

@section('title', auth()->user()->isCasse() ? 'Gestion des pièces détachées' : 'Rechercher des pièces détachées')

@section('content')
    <div class="container-fluid">

        {{-- Titre et bouton Ajouter --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                {{ auth()->user()->isCasse() ? 'Gestion des pièces détachées' : 'Rechercher des pièces détachées' }}
            </h1>
            @if(auth()->user()->isCasse())
                <a href="{{ route('pieces.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter une pièce
                </a>
            @endif
        </div>

        {{-- Filtres --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Nom de la pièce..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="marque" class="form-select">
                            <option value="">Toutes marques</option>
                            @foreach($marques as $marque)
                                <option value="{{ $marque }}" {{ request('marque') == $marque ? 'selected' : '' }}>{{ $marque }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="etat" class="form-select">
                            <option value="">Tous états</option>
                            @foreach(['neuf','tres_bon','bon','moyen','usage'] as $etat)
                                <option value="{{ $etat }}" {{ request('etat') == $etat ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $etat)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="ville" class="form-select">
                            <option value="">Toutes villes</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                    {{ $ville }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('pieces.index') }}" class="btn btn-secondary w-100">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Liste des pièces --}}
        <div class="card shadow">
            <div class="card-body">
                @if($pieces->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Photo</th>
                                <th>Pièce</th>
                                <th>Marque</th>
                                <th>Modèle</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>État</th>
                                <th>Disponible</th>
                                <th>Ville</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pieces as $piece)
                                <tr>
                                    <td>
                                        @if($piece->photos && count($piece->photos) > 0)
                                            <img src="{{ asset('storage/' . $piece->photos[0]) }}" width="60" height="60" class="rounded" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-cog text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $piece->nom }}</strong><br>
                                        @if($piece->reference_constructeur)
                                            <small class="text-muted">Réf: {{ $piece->reference_constructeur }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $piece->marque_piece ?? '-' }}</td>
                                    <td>{{ $piece->modele_piece ?? '-' }}</td>
                                    <td>{{ number_format($piece->prix, 2, ',', ' ') }} FCFA</td>
                                    <td>
                                    <span class="badge bg-{{ $piece->quantite > 10 ? 'success' : ($piece->quantite > 0 ? 'warning' : 'danger') }}">
                                        {{ $piece->quantite }}
                                    </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $piece->etat)) }}</span>
                                    </td>
                                    <td>
                                    <span class="badge bg-{{ $piece->disponible ? 'success' : 'danger' }}">
                                        {{ $piece->disponible ? 'Oui' : 'Non' }}
                                    </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $piece->ville ?? 'Non définie' }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('pieces.show', $piece) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if(auth()->user()->isClient())
                                                @if($piece->disponible && $piece->quantite > 0)
                                                    <button type="button" class="btn btn-sm btn-outline-success add-to-cart-btn" data-piece="{{ $piece->id }}">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button>
                                                @endif
                                            @endif

                                            @if(auth()->user()->isCasse())
                                                <a href="{{ route('pieces.edit', $piece) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('pieces.destroy', $piece) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette pièce ?')" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pieces->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-cog fa-4x text-muted mb-3"></i>
                        <h4>Aucune pièce enregistrée</h4>
                        @if(auth()->user()->isCasse())
                            <p class="text-muted">Commencez par ajouter votre première pièce</p>
                            <a href="{{ route('pieces.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter une pièce
                            </a>
                        @else
                            <p class="text-muted">Aucune pièce ne correspond à vos critères de recherche.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const buttons = document.querySelectorAll('.add-to-cart-btn');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const pieceId = this.getAttribute('data-piece');
                    let url = '{{ route("panier.add", ["piece" => ":piece"]) }}';
                    url = url.replace(':piece', pieceId);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'  // <-- important !

                        },
                        body: JSON.stringify({ quantite: 1 })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showAlert('Pièce ajoutée au panier avec succès !', 'success');
                                // Désactive le bouton
                                button.disabled = true;
                                button.classList.remove('btn-outline-success');
                                button.classList.add('btn-success');
                            } else {
                                showAlert(data.message || 'Erreur lors de l\'ajout au panier', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            showAlert('Erreur lors de l\'ajout au panier', 'danger');
                        });
                });
            });

            function showAlert(message, type) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
                document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.container-fluid').firstChild);
                setTimeout(() => alertDiv.remove(), 3000);
            }

        });
    </script>
@endsection
