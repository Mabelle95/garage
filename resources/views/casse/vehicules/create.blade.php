{{-- resources/views/casse/vehicules/create.blade.php --}}
@extends('layouts.casse')

@section('title', 'Ajouter un Véhicule')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Ajouter un nouveau véhicule</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('casse.vehicules.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="marque">
                            Marque *
                        </label>
                        <input type="text" id="marque" name="marque" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="modele">
                            Modèle *
                        </label>
                        <input type="text" id="modele" name="modele" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="annee">
                            Année *
                        </label>
                        <input type="number" id="annee" name="annee" min="1900" max="{{ date('Y') }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="immatriculation">
                            Immatriculation *
                        </label>
                        <input type="text" id="immatriculation" name="immatriculation" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="type_vehicule">
                            Type de véhicule *
                        </label>
                        <select id="type_vehicule" name="type_vehicule" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Sélectionnez...</option>
                            <option value="voiture">Voiture</option>
                            <option value="moto">Moto</option>
                            <option value="camion">Camion</option>
                            <option value="utilitaire">Utilitaire</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="date_arrivee">
                            Date d'arrivée *
                        </label>
                        <input type="date" id="date_arrivee" name="date_arrivee" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="etat">
                            État général *
                        </label>
                        <select id="etat" name="etat" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="excellent">Excellent</option>
                            <option value="bon" selected>Bon</option>
                            <option value="moyen">Moyen</option>
                            <option value="mauvais">Mauvais</option>
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
                    <a href="{{ route('casse.vehicules') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
