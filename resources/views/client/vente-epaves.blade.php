@extends('layouts.client')

@section('title', 'Vente d\'Épaves')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Vente d'Épaves</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVenteEpave">
            <i class="fas fa-plus"></i> Proposer une épave
        </button>
    </div>

    <!-- Formulaire de vente d'épave (Modal) -->
    <div class="modal fade" id="modalVenteEpave" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Proposer une épave à la vente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('client.vente-epaves.creer') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Marque *</label>
                                <input type="text" name="marque" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Modèle *</label>
                                <input type="text" name="modele" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Année *</label>
                                <input type="number" name="annee" class="form-control"
                                       min="1900" max="{{ date('Y') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Immatriculation</label>
                                <input type="text" name="immatriculation" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">État du véhicule *</label>
                                <select name="etat" class="form-select" required>
                                    <option value="">Sélectionner l'état</option>
                                    <option value="roulant">Roulant</option>
                                    <option value="non_roulant">Non roulant</option>
                                    <option value="accidente">Accidenté</option>
                                    <option value="incendie">Incendié</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Prix souhaité (FCFA)</label>
                                <input type="number" name="prix_souhaite" class="form-control"
                                       step="0.01" min="0" placeholder="Laissez vide pour estimation">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description détaillée *</label>
                                <textarea name="description" class="form-control" rows="4"
                                          placeholder="Décrivez l'état du véhicule, les dommages, l'historique..."
                                          required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Photos du véhicule</label>
                                <input type="file" name="photos[]" class="form-control" multiple
                                       accept="image/*">
                                <small class="text-muted">Vous pouvez sélectionner plusieurs photos</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Soumettre la demande</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($venteEpaves->count() > 0)
        <div class="row">
            @foreach($venteEpaves as $vente)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ $vente->marque }} {{ $vente->modele }} ({{ $vente->annee }})</h6>
                            <span class="badge bg-{{ [
                            'en_attente' => 'warning',
                            'evaluee' => 'info',
                            'acceptee' => 'success',
                            'refusee' => 'danger',
                            'vendue' => 'secondary'
                        ][$vente->statut] }}">{{ $vente->statut }}</span>
                        </div>

                        <div class="card-body">
                            @if($vente->photos && count($vente->photos) > 0)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $vente->photos[0]) }}"
                                         class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                                </div>
                            @endif

                            <p><strong>Immatriculation:</strong> {{ $vente->immatriculation ?? 'Non renseignée' }}</p>
                            <p><strong>État:</strong> {{ ucfirst(str_replace('_', ' ', $vente->etat)) }}</p>
                            <p><strong>Prix souhaité:</strong>
                                {{ $vente->prix_souhaite ? number_format($vente->prix_souhaite, 2) . ' FCFA' : 'À estimer' }}
                            </p>

                            <p><strong>Description:</strong><br>
                                {{ Str::limit($vente->description, 150) }}</p>

                            @if($vente->prix_propose)
                                <div class="alert alert-info">
                                    <strong>Prix proposé:</strong> {{ number_format($vente->prix_propose, 2) }} FCFA
                                    @if($vente->notes_evaluation)
                                        <br><small>{{ $vente->notes_evaluation }}</small>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <small class="text-muted">
                                Soumis le {{ $vente->created_at->format('d/m/Y') }}
                            </small>
                            <button class="btn btn-sm btn-outline-primary float-end"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetailVente{{ $vente->id }}">
                                Détails
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal détail vente -->
                <div class="modal fade" id="modalDetailVente{{ $vente->id }}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Détail de la vente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Contenu détaillé de la vente -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Marque:</strong> {{ $vente->marque }}</p>
                                        <p><strong>Modèle:</strong> {{ $vente->modele }}</p>
                                        <p><strong>Année:</strong> {{ $vente->annee }}</p>
                                        <p><strong>Immatriculation:</strong> {{ $vente->immatriculation ?? 'Non renseignée' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>État:</strong> {{ ucfirst(str_replace('_', ' ', $vente->etat)) }}</p>
                                        <p><strong>Statut:</strong>
                                            <span class="badge bg-{{ [
                                            'en_attente' => 'warning',
                                            'evaluee' => 'info',
                                            'acceptee' => 'success',
                                            'refusee' => 'danger',
                                            'vendue' => 'secondary'
                                        ][$vente->statut] }}">{{ $vente->statut }}</span>
                                        </p>
                                        <p><strong>Prix souhaité:</strong>
                                            {{ $vente->prix_souhaite ? number_format($vente->prix_souhaite, 2) . ' FCFA' : 'À estimer' }}
                                        </p>
                                    </div>
                                </div>

                                <p><strong>Description:</strong><br>{{ $vente->description }}</p>

                                @if($vente->photos && count($vente->photos) > 0)
                                    <div class="row mt-3">
                                        @foreach($vente->photos as $photo)
                                            <div class="col-md-4 mb-2">
                                                <img src="{{ asset('storage/' . $photo) }}"
                                                     class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if($vente->notes_evaluation)
                                    <div class="alert alert-info mt-3">
                                        <strong>Évaluation:</strong><br>
                                        {{ $vente->notes_evaluation }}
                                        @if($vente->prix_propose)
                                            <br><strong>Prix proposé:</strong> {{ number_format($vente->prix_propose, 2) }} FCFA
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-car-crash fa-3x text-muted mb-3"></i>
            <h4>Aucune épave proposée</h4>
            <p class="text-muted">Proposez votre véhicule épave pour obtenir une estimation gratuite</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVenteEpave">
                <i class="fas fa-plus"></i> Proposer une épave
            </button>
        </div>
    @endif
@endsection
