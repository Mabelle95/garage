{{-- resources/views/casse/stock/edit.blade.php --}}
@extends('layouts.casse')

@section('title', 'Modifier la Pièce')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Modifier la pièce</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('casse.stock.update', $piece) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="vehicule_id">
                            Véhicule *
                        </label>
                        <select id="vehicule_id" name="vehicule_id" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @foreach($vehicules as $vehicule)
                                <option value="{{ $vehicule->id }}" {{ $piece->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                    {{ $vehicule->marque }} {{ $vehicule->modele }} ({{ $vehicule->immatriculation }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">
                            Nom de la pièce *
                        </label>
                        <input type="text" id="nom" name="nom" value="{{ $piece->nom }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="reference">
                            Référence *
                        </label>
                        <input type="text" id="reference" name="reference" value="{{ $piece->reference }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="categorie">
                            Catégorie *
                        </label>
                        <select id="categorie" name="categorie" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="moteur" {{ $piece->categorie == 'moteur' ? 'selected' : '' }}>Moteur</option>
                            <option value="carrosserie" {{ $piece->categorie == 'carrosserie' ? 'selected' : '' }}>Carrosserie</option>
                            <option value="interieur" {{ $piece->categorie == 'interieur' ? 'selected' : '' }}>Intérieur</option>
                            <option value="electronique" {{ $piece->categorie == 'electronique' ? 'selected' : '' }}>Électronique</option>
                            <option value="freinage" {{ $piece->categorie == 'freinage' ? 'selected' : '' }}>Freinage</option>
                            <option value="suspension" {{ $piece->categorie == 'suspension' ? 'selected' : '' }}>Suspension</option>
                            <option value="transmission" {{ $piece->categorie == 'transmission' ? 'selected' : '' }}>Transmission</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="prix">
                            Prix (FCFA) *
                        </label>
                        <input type="number" id="prix" name="prix" step="0.01" min="0" value="{{ $piece->prix }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="quantite">
                            Quantité *
                        </label>
                        <input type="number" id="quantite" name="quantite" min="0" value="{{ $piece->quantite }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="etat">
                            État *
                        </label>
                        <select id="etat" name="etat" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="occasion" {{ $piece->etat == 'occasion' ? 'selected' : '' }}>Occasion</option>
                            <option value="reconditionne" {{ $piece->etat == 'reconditionne' ? 'selected' : '' }}>Reconditionné</option>
                            <option value="neuf" {{ $piece->etat == 'neuf' ? 'selected' : '' }}>Neuf</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $piece->description }}</textarea>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('casse.stock') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
