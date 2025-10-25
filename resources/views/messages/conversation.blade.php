@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4">
            Conversation avec
            <strong>{{ $conversation['otherUser']['name'] }}</strong>
            ({{ $conversation['otherUser']['role'] }})
        </h3>

        <div class="card shadow-sm">
            <div class="card-body" style="max-height: 500px; overflow-y: auto;" id="chatWindow">
                @forelse ($conversation['messages'] as $message)
                    <div class="mb-3 {{ $message['expediteur_id'] === auth()->id() ? 'text-end' : 'text-start' }}">

                        {{-- Sujet du message --}}
                        @if(!empty($message['sujet']))
                            <h6 class="fw-bold text-primary mb-1">{{ $message['sujet'] }}</h6>
                        @endif

                        {{-- Contenu --}}
                        <div class="p-2 rounded
                        {{ $message['expediteur_id'] === auth()->id()
                            ? 'bg-primary text-white d-inline-block'
                            : 'bg-light text-dark d-inline-block' }}">
                            {!! nl2br(e($message['contenu'])) !!}
                        </div>

                        {{-- Infos complémentaires --}}
                        <div class="small text-muted mt-1">
                            {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                            @if($message['commande'])
                                • Commande n° {{ $message['commande']['numero_commande'] }}
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">Aucun message pour le moment.</p>
                @endforelse
            </div>

            {{-- Formulaire d’envoi --}}
            <div class="card-footer">
                <form id="messageForm">
                    @csrf
                    <input type="hidden" id="destinataire_id" value="{{ $conversation['otherUser']['id'] }}">

                    <div class="mb-2">
                        <input type="text" id="sujet" class="form-control" placeholder="Sujet (facultatif)">
                    </div>
                    <div class="mb-2">
                        <textarea id="contenu" class="form-control" rows="3" placeholder="Écrivez un message..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const destinataire_id = document.getElementById('destinataire_id').value;
            const sujet = document.getElementById('sujet').value;
            const contenu = document.getElementById('contenu').value;

            fetch('{{ route("messages.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ destinataire_id, sujet, contenu })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de l’envoi du message');
                    }
                })
                .catch(err => console.error(err));
        });
    </script>
@endsection
