{{-- resources/views/casse/ventes-epaves/index.blade.php --}}
@extends('layouts.casse')

@section('title', 'Demandes de Vente d\'Épaves')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Demandes de Vente d'Épaves</h1>
        <p class="text-gray-600">Gérez les demandes de vente de véhicules de vos clients</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Véhicule
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Client
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Prix souhaité
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        État
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($demandes as $demande)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div class="ml-3">
                                    <p class="text-gray-900 whitespace-no-wrap font-medium">
                                        {{ $demande->marque }} {{ $demande->modele }}
                                    </p>
                                    <p class="text-gray-600 text-xs">{{ $demande->annee }} - {{ $demande->immatriculation }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $demande->client->name }}</p>
                            <p class="text-gray-600 text-xs">{{ $demande->client->email }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                @if($demande->prix_souhaite)
                                    {{ number_format($demande->prix_souhaite, 2) }}FCFA
                                @else
                                    <span class="text-gray-400">Non spécifié</span>
                                @endif
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $demande->statut === 'acceptee' ? 'bg-green-100 text-green-800' :
                               ($demande->statut === 'refusee' ? 'bg-red-100 text-red-800' :
                               ($demande->statut === 'evaluee' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ str_replace('_', ' ', $demande->statut) }}
                        </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $demande->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="{{ route('casse.ventes-epaves.show', $demande) }}"
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            {{-- Dans resources/views/casse/ventes-epaves/index.blade.php --}}
                            @if($demande->statut === 'en_attente' || $demande->statut === 'en_cours')
                                <a href="{{ route('casse.ventes-epaves.evaluer-form', $demande) }}"
                                   class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-euro-sign"></i> Évaluer
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($demandes->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center mt-6">
            <i class="fas fa-car-crash text-4xl text-yellow-400 mb-3"></i>
            <h3 class="text-lg font-semibold text-yellow-800">Aucune demande de vente d'épave</h3>
            <p class="text-yellow-600">Les demandes de vos clients apparaîtront ici.</p>
        </div>
    @endif
@endsection
