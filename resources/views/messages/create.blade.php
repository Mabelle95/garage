@extends('layouts.app')

@section('title', 'Nouveau message')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour aux messages
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-envelope me-2"></i>Nouveau message</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('messages.store') }}" method="POST">
                            @csrf

                            @if(isset($destinataire))
                                <input type="hidden" name="destinataire_id" value="{{ $destinataire->id }}">
                                <div class="mb-3">
                                    <label class="form-label">Destinataire</label>
                                    <div class="form-control-plaintext bg-light p-2 rounded">
                                        <strong>{{ $destinataire->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $destinataire->email }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <label for="destinataire_id" class="form-label">Destinataire *</label>
                                    <select class="form-select @error('destinataire_id') is-invalid @enderror"
                                            id="destinataire_id"
                                            name="destinataire_id"
                                            required>
                                        <option value="">-- Sélectionner un destinataire --</option>
                                        @php
                                            $users = \App\Models\User::where('id', '!=', auth()->id())
                                                ->orderBy('name')
                                                ->get();
                                        @endphp
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('destinataire_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ ucfirst($user->role->value) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('destinataire_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            @if(isset($commande))
                                <input type="hidden" name="commande_id" value="{{ $commande->id }}">
                                <div class="mb-3">
                                    <label class="form-label">Commande associée</label>
                                    <div class="form-control-plaintext bg-light p-2 rounded">
                                        <a href="{{ route('commandes.show', $commande) }}" class="badge bg-info text-decoration-none">
                                            {{ $commande->numero_commande }}
                                        </a>
                                        <span class="ms-2">- {{ number_format($commande->total, 2) }} FCFA</span>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="sujet" class="form-label">Sujet *</label>
                                <input type="text"
                                       class="form-control @error('sujet') is-invalid @enderror"
                                       id="sujet"
                                       name="sujet"
                                       value="{{ old('sujet') }}"
                                       required
                                       maxlength="255">
                                @error('sujet')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contenu" class="form-label">Message *</label>
                                <textarea class="form-control @error('contenu') is-invalid @enderror"
                                          id="contenu"
                                          name="contenu"
                                          rows="10"
                                          required
                                          maxlength="5000">{{ old('contenu') }}</textarea>
                                <small class="text-muted">Maximum 5000 caractères</small>
                                @error('contenu')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Envoyer le message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
