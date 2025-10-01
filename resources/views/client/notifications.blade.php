@extends('layouts.client')

@section('title', 'Mes Notifications')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mes Notifications</h1>
        <div>
            <span class="badge bg-primary me-2">{{ $notifications->total() }} notification(s)</span>
            <button class="btn btn-outline-secondary btn-sm" onclick="marquerToutesLues()">
                <i class="fas fa-check-double"></i> Tout marquer comme lu
            </button>
        </div>
    </div>

    @if($notifications->count() > 0)
        <div class="card">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <div class="list-group-item {{ is_null($notification->read_at) ? 'list-group-item-light' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-{{ [
                                        'commande' => 'shopping-cart',
                                        'livraison' => 'truck',
                                        'stock' => 'box',
                                        'promotion' => 'tag',
                                        'systeme' => 'info-circle'
                                    ][$notification->type] ?? 'bell' }} text-primary me-2"></i>

                                        <h6 class="mb-0">{{ $notification->title }}</h6>

                                        @if(is_null($notification->read_at))
                                            <span class="badge bg-warning ms-2">Nouveau</span>
                                        @endif
                                    </div>

                                    <p class="mb-1">{{ $notification->message }}</p>

                                    @if($notification->data)
                                        <div class="mt-2">
                                            @if(isset($notification->data['commande_id']))
                                                <a href="{{ route('client.detail-commande', $notification->data['commande_id']) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    Voir la commande
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="text-end">
                                    <small class="text-muted d-block">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>

                                    @if(is_null($notification->read_at))
                                        <button class="btn btn-sm btn-outline-success mt-1"
                                                onclick="marquerCommeLue({{ $notification->id }})">
                                            <i class="fas fa-check"></i> Marquer lu
                                        </button>
                                    @else
                                        <small class="text-muted d-block">
                                            Lu {{ $notification->read_at->diffForHumans() }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
            <h4>Aucune notification</h4>
            <p class="text-muted">Vous n'avez aucune notification pour le moment</p>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        function marquerCommeLue(notificationId) {
            fetch(`/client/notifications/${notificationId}/marquer-lue`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function marquerToutesLues() {
            if (confirm('Marquer toutes les notifications comme lues ?')) {
                fetch('/client/notifications/marquer-toutes-lues', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        }
    </script>
@endsection
