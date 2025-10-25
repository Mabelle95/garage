{{-- resources/views/casse/commandes/index.blade.php --}}
@extends('layouts.casse')

@section('title', 'Gestion des Commandes')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Gestion des Commandes</h1>
        <p class="text-gray-600">Suivez et gérez les commandes de vos clients</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Commande
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Client
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Total
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Statut
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
                @foreach($commandes as $commande)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap font-bold">
                                #{{ $commande->numero_commande }}
                            </p>
                            <p class="text-gray-600 text-xs">{{ $commande->count() }} articles</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $commande->client->name }}</p>
                            <p class="text-gray-600 text-xs">{{ $commande->client->email }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap font-bold">{{ number_format($commande->total, 2) }} FCFA</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <form action="{{ route('casse.commandes.update', $commande) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="statut" onchange="this.form.submit()"
                                        class="text-xs rounded border-0 focus:ring-2 focus:ring-blue-500
                                    {{ $commande->statut === 'livree' ? 'bg-green-100 text-green-800' :
                                       ($commande->statut === 'annulee' ? 'bg-red-100 text-red-800' :
                                       ($commande->statut === 'expediee' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    <option value="en_attente" {{ $commande->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmee" {{ $commande->statut === 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="preparation" {{ $commande->statut === 'preparation' ? 'selected' : '' }}>En préparation</option>
                                    <option value="expediee" {{ $commande->statut === 'expediee' ? 'selected' : '' }}>Expédiée</option>
                                    <option value="livree" {{ $commande->statut === 'livree' ? 'selected' : '' }}>Livrée</option>
                                    <option value="annulee" {{ $commande->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <button onclick="toggleDetails({{ $commande->id }})"
                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                <i class="fas fa-eye mr-1"></i>Détails
                            </button>
                            <a href="#" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editStatutModal{{ $commande->id }}">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                        </td>
                    </tr>
                    <tr id="details-{{ $commande->id }}" class="hidden">
                        <td colspan="6" class="px-5 py-4 bg-gray-50">
                            <div class="bg-white rounded-lg p-4 shadow-inner">
                                <h4 class="font-semibold mb-3">Détails de la commande</h4>
                                <div class="space-y-2">
                                    @foreach($commande->pieces as $piece)
                                        <div class="flex justify-between items-center border-b pb-2">
                                            <div>
                                                <p class="font-medium">{{ $piece->nom }}</p>
                                                <p class="text-sm text-gray-600">Réf: {{ $piece->reference }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p>{{ $piece->pivot->quantite }} x {{ number_format($piece->pivot->prix_unitaire, 2) }} FCFA</p>
                                                <p class="font-bold">{{ number_format($piece->pivot->quantite * $piece->pivot->prix_unitaire, 2) }} FCFA</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 pt-3 border-t">
                                    <p class="text-right font-bold text-lg">Total: {{ number_format($commande->total, 2) }} FCFA</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($commandes->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center mt-6">
            <i class="fas fa-shopping-cart text-4xl text-yellow-400 mb-3"></i>
            <h3 class="text-lg font-semibold text-yellow-800">Aucune commande pour le moment</h3>
            <p class="text-yellow-600">Les commandes de vos clients apparaîtront ici.</p>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        function toggleDetails(commandeId) {
            const details = document.getElementById('details-' + commandeId);
            details.classList.toggle('hidden');
        }
    </script>
@endsection
