@extends('layouts.app')

@section('title', 'Mes notifications')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Mes notifications</h1>
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-check-double"></i> Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>

        <div class="card shadow">
            <div class="card-body">
                @if($notifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item {{ $notification->lu ? '' : 'bg-light' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $notification->titre }}</h6>
                                        <p class="mb-1">{{ $notification->message }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="ms-3">
                                        @if(!$notification->lu)
                                            <form action="{{ route('notifications.read', $notification) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Marquer comme lu">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                        <h4>Aucune notification</h4>
                        <p class="text-muted">Vous n'avez aucune notification pour le moment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
