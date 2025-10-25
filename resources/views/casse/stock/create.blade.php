{{-- resources/views/casse/stock/create.blade.php --}}
@extends('layouts.casse')

@section('title', 'Ajouter une Pièce')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Ajouter une nouvelle pièce</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('casse.stock.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="vehicule_id">
                            Véhicule *
                        </label>
                        <select id="vehicule_id" name="vehicule_id" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Sélectionnez un véhicule</option>
                            @foreach($vehicules as $vehicule)
                                <option value="{{ $vehicule->id }}">
                                    {{ $vehicule->marque }} {{ $vehicule->modele }} ({{ $vehicule->immatriculation }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">
                            Nom de la pièce *
                        </label>
                        <input type="text" id="nom" name="nom" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="reference">
                            Référence *
                        </label>
                        <input type="text" id="reference" name="reference" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="categorie">
                            Catégorie *
                        </label>
                        <select id="categorie" name="categorie" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Sélectionnez...</option>
                            <option value="moteur">Moteur</option>
                            <option value="carrosserie">Carrosserie</option>
                            <option value="interieur">Intérieur</option>
                            <option value="electronique">Électronique</option>
                            <option value="freinage">Freinage</option>
                            <option value="suspension">Suspension</option>
                            <option value="transmission">Transmission</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="prix">
                            Prix (FCFA) *
                        </label>
                        <input type="number" id="prix" name="prix" step="0.01" min="0" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="quantite">
                            Quantité *
                        </label>
                        <input type="number" id="quantite" name="quantite" min="0" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="etat">
                            État *
                        </label>
                        <select id="etat" name="etat" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="occasion">Occasion</option>
                            <option value="reconditionne">Reconditionné</option>
                            <option value="neuf">Neuf</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('casse.stock') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </a>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
