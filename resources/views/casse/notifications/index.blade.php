{{-- resources/views/casse/notifications/index.blade.php --}}
@extends('layouts.casse')

@section('title', 'Notifications')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Notifications</h1>
            <p class="text-gray-600">Restez informé de l'activité de votre casse</p>
        </div>
        @if($notifications->whereNull('read_at')->count() > 0)
            <form action="{{ route('casse.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-check-double mr-2"></i>Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                @if(is_null($notification->read_at))
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                @endif
                                <h3 class="font-semibold text-gray-900">{{ $notification->title }}</h3>
                                <span class="ml-2 text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 mb-2">{{ $notification->message }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('casse.notifications.show', $notification) }}"
                                   class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fas fa-eye mr-1"></i>Voir les détails
                                </a>
                                <form action="{{ route('casse.notifications.destroy', $notification) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                        <i class="fas fa-trash mr-1"></i>Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-600">Aucune notification</h3>
                    <p class="text-gray-500">Vous serez notifié dès qu'il y aura une nouvelle activité.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
@endsection
