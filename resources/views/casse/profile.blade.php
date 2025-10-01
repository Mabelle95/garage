{{-- resources/views/casse/profile.blade.php --}}
@extends('layouts.casse')

@section('title', 'Mon Profil')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Mon Profil Professionnel</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('casse.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Nom de la casse *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $casse->name) }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email *
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $casse->email) }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="telephone">
                            Téléphone
                        </label>
                        <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $casse->telephone) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="siret">
                            SIRET
                        </label>
                        <input type="text" id="siret" name="siret" value="{{ old('siret', $casse->siret) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="adresse">
                            Adresse
                        </label>
                        <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $casse->adresse) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="ville">
                            Ville
                        </label>
                        <input type="text" id="ville" name="ville" value="{{ old('ville', $casse->ville) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="code_postal">
                            Code Postal
                        </label>
                        <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal', $casse->code_postal) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Description de la casse
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $casse->description) }}</textarea>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-save mr-2"></i>Mettre à jour le profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistiques de la casse -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-car text-2xl text-blue-500 mr-3"></i>
                    <div>
                        <p class="text-2xl font-bold">{{ $casse->vehicules->count() }}</p>
                        <p class="text-blue-600">Véhicules en stock</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-boxes text-2xl text-green-500 mr-3"></i>
                    <div>
                        <p class="text-2xl font-bold">{{ $casse->pieces->count() }}</p>
                        <p class="text-green-600">Pièces disponibles</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-shopping-cart text-2xl text-purple-500 mr-3"></i>
                    <div>
                        <p class="text-2xl font-bold">{{ $casse->commandes->count() }}</p>
                        <p class="text-purple-600">Commandes totales</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
