@extends('layouts.app')

@section('title', 'Modifier une pièce détachée')

@section('content')
    <div class="container">

        <h1 class="mb-4">Modifier une pièce détachée</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('pieces.update', $piece) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nom --}}
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la pièce *</label>
                <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $piece->nom) }}">
                @error('nom')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Marque --}}
            <div class="mb-3">
                <label for="marque_piece" class="form-label">Marque de la pièce *</label>
                <input type="text" name="marque_piece" id="marque_piece" class="form-control @error('marque_piece') is-invalid @enderror" value="{{ old('marque_piece', $piece->marque_piece) }}">
                @error('marque_piece')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Modèle --}}
            <div class="mb-3">
                <label for="modele_piece" class="form-label">Modèle de la pièce *</label>
                <input type="text" name="modele_piece" id="modele_piece" class="form-control @error('modele_piece') is-invalid @enderror" value="{{ old('modele_piece', $piece->modele_piece) }}">
                @error('modele_piece')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $piece->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Prix --}}
            <div class="mb-3">
                <label for="prix" class="form-label">Prix (≥ 500 FCFA) *</label>
                <input type="number" name="prix" id="prix" class="form-control @error('prix') is-invalid @enderror" value="{{ old('prix', $piece->prix) }}" min="500">
                @error('prix')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Quantité --}}
            <div class="mb-3">
                <label for="quantite" class="form-label">Quantité disponible (≥ 1) *</label>
                <input type="number" name="quantite" id="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite', $piece->quantite) }}" min="1">
                @error('quantite')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- État --}}
            <div class="mb-3">
                <label for="etat" class="form-label">État *</label>
                <select name="etat" id="etat" class="form-select @error('etat') is-invalid @enderror" required>
                    <option value="">Sélectionner un état</option>
                    @foreach(['neuf','tres_bon','bon','moyen','usage'] as $etatOption)
                        <option value="{{ $etatOption }}" {{ old('etat', $piece->etat) == $etatOption ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $etatOption)) }}
                        </option>
                    @endforeach
                </select>
                @error('etat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Référence constructeur --}}
            <div class="mb-3">
                <label for="reference_constructeur" class="form-label">Référence constructeur *</label>
                <input type="text" name="reference_constructeur" id="reference_constructeur" class="form-control @error('reference_constructeur') is-invalid @enderror" value="{{ old('reference_constructeur', $piece->reference_constructeur) }}">
                @error('reference_constructeur')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Compatible avec --}}
            <div class="mb-3">
                <label for="compatible_avec" class="form-label">Compatible avec *</label>
                <input type="text" name="compatible_avec" id="compatible_avec" class="form-control @error('compatible_avec') is-invalid @enderror" value="{{ old('compatible_avec', $piece->compatible_avec) }}">
                @error('compatible_avec')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ville --}}
            <div class="mb-3">
                <label for="ville" class="form-label">Ville *</label>
                <input type="text" name="ville" id="ville" class="form-control @error('ville') is-invalid @enderror" value="{{ old('ville', $piece->ville) }}">
                @error('ville')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Photos --}}
            <div class="mb-3">
                <label for="photos" class="form-label">Photos (plusieurs possibles)</label>
                <input type="file" name="photos[]" id="photos" class="form-control @error('photos.*') is-invalid @enderror" multiple>
                @error('photos.*')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if($piece->photos)
                    <div class="mt-2">
                        <p>Photos existantes :</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($piece->photos as $photo)
                                <div class="border p-1">
                                    <img src="{{ asset('storage/'.$photo) }}" alt="Photo" style="height: 80px;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Disponible --}}
            <div class="form-check mb-3">
                <input type="checkbox" name="disponible" id="disponible" class="form-check-input" {{ old('disponible', $piece->disponible) ? 'checked' : '' }}>
                <label for="disponible" class="form-check-label">Disponible</label>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour la pièce</button>
        </form>
    </div>
@endsection
