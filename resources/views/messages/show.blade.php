@extends('layouts.app')

@section('title', 'Message')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour aux messages
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">{{ $message->sujet }}</h4>
            </div>
            <div class="card-body">
                <!-- Informations du message -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong><i class="fas fa-user me-2"></i>De :</strong>
                        <span class="ms-2">{{ $message->expediteur->name }}</span>
                        <br>
                        <small class="text-muted ms-4">{{ $message->expediteur->email }}</small>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-user me-2"></i>À :</strong>
                        <span class="ms-2">{{ $message->destinataire->name }}</span>
                        <br>
                        <small class="text-muted ms-4">{{ $message->destinataire->email }}</small>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong><i class="fas fa-clock me-2"></i>Date :</strong>
                        <span class="ms-2">{{ $message->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    @if($message->commande)
                        <div class="col-md-6">
                            <strong><i class="fas fa-shopping-cart me-2"></i>Commande associée :</strong>
                            <a href="{{ route('commandes.show', $message->commande) }}" class="badge bg-info ms-2">
                                {{ $message->commande->numero_commande }}
                            </a>
                        </div>
                    @endif
                </div>

                @if($message->destinataire_id === auth()->id() && $message->lu)
                    <div class="alert alert-info">
                        <i class="fas fa-check-double me-2"></i>
                        <small>Lu le {{ $message->lu_at->format('d/m/Y à H:i') }}</small>
                    </div>
                @endif

                <hr>

                <!-- Contenu du message -->
                <div class="message-content bg-light p-4 rounded">
                    {!! nl2br(e($message->contenu)) !!}
                </div>

                <!-- Actions -->
                <div class="mt-4">
                    @if($message->destinataire_id === auth()->id())
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#repondreModal">
                            <i class="fas fa-reply me-1"></i> Répondre
                        </button>
                        <form action="{{ route('messages.destroy', $message) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Supprimer ce message ?')">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Répondre -->
    <div class="modal fade" id="repondreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Répondre à {{ $message->expediteur->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="destinataire_id" value="{{ $message->expediteur_id }}">
                    @if($message->commande_id)
                        <input type="hidden" name="commande_id" value="{{ $message->commande_id }}">
                    @endif
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="sujet" class="form-label">Sujet</label>
                            <input type="text" class="form-control" id="sujet" name="sujet" value="Re: {{ $message->sujet }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="contenu" class="form-label">Message</label>
                            <textarea class="form-control" id="contenu" name="contenu" rows="8" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
