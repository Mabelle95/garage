<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏢 Tableau de bord - Casse automobile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <span class="text-2xl">🚗</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Véhicules</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['vehicules'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <span class="text-2xl">🔧</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pièces en stock</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pieces'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <span class="text-2xl">📦</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Commandes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['commandes'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <span class="text-2xl">💰</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Chiffre d'affaires</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['chiffre_affaires'], 2) }} FCFA</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <a href="{{ route('casse.vehicules') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border hover:border-blue-500 transition">
                    <div class="text-center">
                        <span class="text-4xl">🚗</span>
                        <h3 class="mt-2 font-medium">Véhicules</h3>
                        <p class="text-sm text-gray-600">Gérer les véhicules</p>
                    </div>
                </a>

                <a href="{{ route('casse.stock') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border hover:border-green-500 transition">
                    <div class="text-center">
                        <span class="text-4xl">🔧</span>
                        <h3 class="mt-2 font-medium">Stock</h3>
                        <p class="text-sm text-gray-600">Gérer les pièces</p>
                    </div>
                </a>

                <a href="{{ route('casse.commandes') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border hover:border-yellow-500 transition">
                    <div class="text-center">
                        <span class="text-4xl">📦</span>
                        <h3 class="mt-2 font-medium">Commandes</h3>
                        <p class="text-sm text-gray-600">Voir les commandes</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border hover:border-purple-500 transition">
                    <div class="text-center">
                        <span class="text-4xl">⚙️</span>
                        <h3 class="mt-2 font-medium">Profil</h3>
                        <p class="text-sm text-gray-600">Modifier le profil</p>
                    </div>
                </a>
            </div>

            <!-- Dernières commandes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-medium">📋 Dernières commandes</h3>
                </div>
                <div class="p-6">
                    @if($commandes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Commande</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach($commandes as $commande)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('casse.commandes') }}" class="text-blue-600 hover:underline">
                                                {{ $commande->numero_commande }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $commande->client->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($commande->total, 2) }} FCFA</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $commande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $commande->statut === 'confirmee' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $commande->statut === 'expediee' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $commande->date_commande->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Aucune commande pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
