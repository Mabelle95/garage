{{-- resources/views/casse/notifications/show.blade.php --}}
@extends('layouts.casse')

@section('title', 'Détails de la Notification')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Détails de la Notification</h1>
            <a href="{{ route('casse.notifications') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $notification->title }}</h2>
                    <span class="text-sm text-gray-500">{{ $notification->created_at->format('d/m/Y à H:i') }}</span>
                </div>

                <div class="flex items-center space-x-4">
                <span class="px-2 py-1 rounded-full text-xs
                    {{ is_null($notification->read_at) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ is_null($notification->read_at) ? 'Non lue' : 'Lue' }}
                </span>
                    <span class="text-sm text-gray-600">
                    <i class="fas fa-tag mr-1"></i>{{ $notification->type }}
                </span>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Message</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-800 whitespace-pre-line">{{ $notification->message }}</p>
                </div>
            </div>

            @if($notification->data)
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Informations supplémentaires</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif

            <!-- Actions selon le type de notification -->
            @if($notification->type === 'nouvelle_commande' && isset($notification->data['commande_id']))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-blue-800 mb-2">Action rapide</h4>
                    <a href="{{ route('casse.commandes') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i>Voir la commande
                    </a>
                </div>
            @endif

            @if($notification->type === 'nouvelle_demande_epave' && isset($notification->data['vente_epave_id']))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-green-800 mb-2">Action rapide</h4>
                    <a href="{{ route('casse.ventes-epaves.show', $notification->data['vente_epave_id']) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-car-crash mr-2"></i>Évaluer le véhicule
                    </a>
                </div>
            @endif

            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
            <span class="text-sm text-gray-500">
                @if($notification->read_at)
                    Lu le {{ $notification->read_at->format('d/m/Y à H:i') }}
                @else
                    <span class="text-blue-600">● Non lue</span>
                @endif
            </span>

                <div class="flex space-x-2">
                    <form action="{{ route('casse.notifications.destroy', $notification) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Navigation entre notifications -->
        <div class="flex justify-between mt-6">
            @php
                $previous = \App\Models\Notification::where('user_id', auth()->id())
                    ->where('id', '<', $notification->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $next = \App\Models\Notification::where('user_id', auth()->id())
                    ->where('id', '>', $notification->id)
                    ->orderBy('id', 'asc')
                    ->first();
            @endphp

            @if($previous)
                <a href="{{ route('casse.notifications.show', $previous) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-chevron-left mr-2"></i>Précédente
                </a>
            @else
                <span class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded inline-flex items-center cursor-not-allowed">
            <i class="fas fa-chevron-left mr-2"></i>Précédente
        </span>
            @endif

            @if($next)
                <a href="{{ route('casse.notifications.show', $next) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    Suivante<i class="fas fa-chevron-right ml-2"></i>
                </a>
            @else
                <span class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded inline-flex items-center cursor-not-allowed">
            Suivante<i class="fas fa-chevron-right ml-2"></i>
        </span>
            @endif
        </div>
    </div>
@endsection
