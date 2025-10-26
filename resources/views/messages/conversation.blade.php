@extends('layouts.app')

@section('title', 'Conversation')

@section('styles')
    <style>
        /* Supprimer le padding du container principal */
        .container-fluid.px-0 {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Container principal avec hauteur fixe */
        .chat-main-container {
            height: calc(100vh - 140px);
            position: relative;
            overflow: hidden;
        }

        /* Container du chat */
        .chat-container {
            height: 100%;
            display: flex;
            overflow: hidden;
            background: #fff;
        }

        /* Zone de chat (pleine largeur) */
        .chat-window {
            flex: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            background: #fff;
            overflow: hidden;
        }

        /* Header du chat */
        .chat-header {
            padding: 15px 20px;
            border-bottom: 2px solid #e9ecef;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex-shrink: 0;
        }

        /* Avatars */
        .conversation-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            flex-shrink: 0;
        }

        .conversation-avatar.casse {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .conversation-avatar.client {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .conversation-avatar.admin {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        /* Zone des messages avec scroll */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px;
            background: #f8f9fa;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.03) 10px, rgba(255,255,255,.03) 20px);
        }

        /* Scrollbar personnalisée */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Bulles de message */
        .message-bubble {
            max-width: 70%;
            margin-bottom: 15px;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-bubble.sent {
            margin-left: auto;
        }

        .message-bubble.received {
            margin-right: auto;
        }

        .message-sender-name {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .message-content {
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
        }

        .message-bubble.sent .message-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message-bubble.received .message-content {
            background: white;
            color: #333;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 5px;
        }

        .commande-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 11px;
            margin-top: 6px;
        }

        .message-bubble.received .commande-badge {
            background: #e3f2fd;
            color: #1976d2;
        }

        /* Zone de saisie */
        .chat-input-container {
            padding: 15px 20px;
            border-top: 1px solid #dee2e6;
            background: white;
            flex-shrink: 0;
        }

        .chat-input {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .chat-input textarea {
            flex: 1;
            min-height: 42px;
            max-height: 150px;
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 21px;
            resize: none;
            font-size: 14px;
            line-height: 1.4;
            transition: border-color 0.2s;
        }

        .chat-input textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .send-button {
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .send-button:hover:not(:disabled) {
            transform: scale(1.05);
        }

        .send-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* État vide */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chat-main-container {
                height: calc(100vh - 100px);
            }

            .message-bubble {
                max-width: 85%;
            }

            .chat-header {
                padding: 12px 15px;
            }

            .conversation-avatar {
                width: 38px;
                height: 38px;
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-0">
        <div class="chat-main-container">
            <div class="chat-container">
                <div class="chat-window" id="chatWindow">
                    <!-- En-tête du chat -->
                    <div class="chat-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('messages.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <div class="conversation-avatar {{ $conversation['otherUser']['role'] }}">
                                    {{ strtoupper(substr($conversation['otherUser']['name'], 0, 2)) }}
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0" style="font-size: 16px;">{{ $conversation['otherUser']['name'] }}</h5>
                                    <small class="opacity-75">
                                        <i class="fas fa-circle text-success" style="font-size: 8px;"></i>
                                        {{ ucfirst($conversation['otherUser']['role']) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="chat-messages" id="chatMessages">
                        @forelse ($conversation['messages'] as $message)
                            @php
                                $isSent = $message['expediteur_id'] === auth()->id();
                            @endphp
                            <div class="message-bubble {{ $isSent ? 'sent' : 'received' }}">
                                @if(!$isSent)
                                    <div class="message-sender-name">{{ $message['expediteur']['name'] }}</div>
                                @endif
                                <div class="message-content">
                                    {!! nl2br(e($message['contenu'])) !!}
                                    @if(isset($message['commande']))
                                        <div class="commande-badge">
                                            <i class="fas fa-shopping-cart me-1"></i>
                                            {{ $message['commande']['numero_commande'] }}
                                        </div>
                                    @endif
                                </div>
                                <div class="message-time">
                                    {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                                    @if($isSent && $message['lu'])
                                        <i class="fas fa-check-double ms-1"></i>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-comments"></i>
                                <p>Aucun message pour le moment.</p>
                                <small class="text-muted">Commencez la conversation !</small>
                            </div>
                        @endforelse
                    </div>

                    <!-- Zone de saisie -->
                    <div class="chat-input-container">
                        <form onsubmit="sendMessage(event)" id="messageForm">
                            @csrf
                            <div class="chat-input">
                            <textarea
                                id="messageInput"
                                placeholder="Écrivez votre message..."
                                rows="1"
                                onkeypress="handleKeyPress(event)"
                                required
                            ></textarea>
                                <button type="submit" class="send-button" id="sendButton">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const otherUserId = {{ $conversation['otherUser']['id'] }};

        // Scroller vers le bas au chargement
        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottom();
            document.getElementById('messageInput').focus();

            // Auto-resize du textarea
            const textarea = document.getElementById('messageInput');
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });

        // Envoyer un message
        function sendMessage(event) {
            event.preventDefault();

            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();

            if (!message) return;

            const sendButton = document.getElementById('sendButton');
            sendButton.disabled = true;

            fetch('{{ route("messages.send-quick") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    destinataire_id: otherUserId,
                    contenu: message,
                    sujet: 'Conversation'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        messageInput.style.height = 'auto';
                        // Recharger la page pour afficher le nouveau message
                        location.reload();
                    } else {
                        alert('Erreur lors de l\'envoi du message');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de l\'envoi du message');
                })
                .finally(() => {
                    sendButton.disabled = false;
                });
        }

        // Gestion de la touche Entrée
        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage(event);
            }
        }

        // Scroller vers le bas
        function scrollToBottom() {
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Actualiser la conversation toutes les 10 secondes
        setInterval(() => {
            location.reload();
        }, 10000);
    </script>
@endsection
