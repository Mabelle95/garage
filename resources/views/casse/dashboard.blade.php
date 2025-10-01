{{-- resources/views/casse/dashboard.blade.php --}}
@extends('layouts.casse')

@section('title', 'Dashboard Casse')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-car text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">{{ $stats['vehicules'] }}</h3>
                    <p class="text-gray-600">Véhicules</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">{{ $stats['pieces'] }}</h3>
                    <p class="text-gray-600">Pièces en stock</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">{{ $stats['commandes'] }}</h3>
                    <p class="text-gray-600">Commandes</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                    <i class="fas fa-euro-sign text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold">{{ number_format($stats['revenus'], 2) }} FCFA</h3>
                    <p class="text-gray-600">Revenus totaux</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Commandes récentes</h3>
            <div class="space-y-4">
                @foreach($commandesRecentes as $commande)
                    <div class="flex justify-between items-center border-b pb-3">
                        <div>
                            <p class="font-medium">Commande #{{ $commande->numero_commande }}</p>
                            <p class="text-sm text-gray-600">{{ $commande->client->name }}</p>
                        </div>
                        <span class="px-2 py-1 rounded text-xs
                    {{ $commande->statut === 'livree' ? 'bg-green-100 text-green-800' :
                       ($commande->statut === 'annulee' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ str_replace('_', ' ', $commande->statut) }}
                </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Actions rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('casse.vehicules.create') }}" class="block w-full text-left p-3 bg-blue-50 hover:bg-blue-100 rounded">
                    <i class="fas fa-plus mr-2"></i>Ajouter un véhicule
                </a>
                <a href="{{ route('casse.stock.create') }}" class="block w-full text-left p-3 bg-green-50 hover:bg-green-100 rounded">
                    <i class="fas fa-plus mr-2"></i>Ajouter une pièce
                </a>
                <a href="{{ route('casse.commandes') }}" class="block w-full text-left p-3 bg-yellow-50 hover:bg-yellow-100 rounded">
                    <i class="fas fa-shopping-cart mr-2"></i>Voir les commandes
                </a>
            </div>
        </div>
    </div>
@endsection
