{{-- resources/views/casse/ventes-epaves/evaluer.blade.php --}}
@extends('layouts.casse')

@section('title', 'Évaluer une Épave')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Évaluation du Véhicule</h1>
            <a href="{{ route('casse.ventes-epaves') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Informations du Véhicule</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div><strong>Marque:</strong> {{ $venteEpave->marque }}</div>
                <div><strong>Modèle:</strong> {{ $venteEpave->modele }}</div>
                <div><strong>Année:</strong> {{ $venteEpave->annee }}</div>
                <div><strong>Immatriculation:</strong> {{ $venteEpave->immatriculation }}</div>
            </div>

            <div class="mb-4">
                <strong>Description:</strong>
                <p class="text-gray-700 mt-1">{{ $venteEpave->description }}</p>
            </div>

            <div>
                <strong>Prix souhaité par le client:</strong>
                <p class="text-lg font-semibold text-blue-600">
                    {{ $venteEpave->prix_souhaite ? number_format($venteEpave->prix_souhaite, 2) . ' FCFA' : 'Non spécifié' }}
                </p>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('casse.ventes-epaves.evaluer', $venteEpave) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="notes_evaluation">
                        Rapport d'évaluation détaillé *
                    </label>
                    <textarea id="notes_evaluation" name="notes_evaluation" rows="6" required
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                              placeholder="Décrivez en détail l'état du véhicule :
- État général de la carrosserie
- État mécanique
- Pièces manquantes ou endommagées
- Kilométrage (si disponible)
- Réparations nécessaires
- Valeur estimée des pièces récupérables"></textarea>
                    <p class="text-sm text-gray-500 mt-1">Cette évaluation sera visible par le client.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="prix_propose">
                            Prix proposé (FCFA) *
                        </label>
                        <input type="number" id="prix_propose" name="prix_propose" step="0.01" min="0" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               placeholder="0.00">
                        <p class="text-sm text-gray-500 mt-1">Prix d'achat que vous proposez au client.</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="statut">
                            Décision finale *
                        </label>
                        <select id="statut" name="statut" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Choisir une décision</option>
                            <option value="acceptee">✅ Accepter - Faire une offre d'achat</option>
                            <option value="refusee">❌ Refuser - Véhicule non intéressant</option>
                        </select>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Information importante
                    </h3>
                    <p class="text-blue-700 text-sm">
                        Une fois l'évaluation soumise, le client sera notifié de votre décision et pourra accepter ou refuser votre offre.
                        Vous ne pourrez plus modifier l'évaluation après envoi.
                    </p>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('casse.ventes-epaves') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </a>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-paper-plane mr-2"></i>Envoyer l'évaluation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
