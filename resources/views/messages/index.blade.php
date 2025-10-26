@extends('layouts.app')

@section('title', 'Messagerie')

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
        }

        /* Sidebar des conversations */
        .conversations-sidebar {
            width: 350px;
            min-width: 300px;
            height: 100%;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #dee2e6;
            background: #fff;
            flex-shrink: 0;
        }

        /* Header des conversations */
        .conversations-header {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            flex-shrink: 0;
            background: #fff;
        }

        /* Barre de recherche */
        .search-box {
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            flex-shrink: 0;
            background: #fff;
        }

        .search-box input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Zone scrollable des conversations */
        .conversations-list {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Scrollbar personnalisée */
        .conversations-list::-webkit-scrollbar,
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .conversations-list::-webkit-scrollbar-track,
        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .conversations-list::-webkit-scrollbar-thumb,
        .chat-messages::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .conversations-list::-webkit-scrollbar-thumb:hover,
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Item de conversation */
        .conversation-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
        }

        .conversation-item:hover {
            background: #f8f9fa;
        }

        .conversation-item.active {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
        }

        .conversation-item.unread {
            background: #f0f7ff;
            font-weight: 600;
        }

        .conversation-item .unread-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 8px;
            height: 8px;
        }

        /* Avatars */
        .conversation-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .conversation-avatar.casse {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .conversation-avatar.client {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        /* Zone de chat */
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

        .message-bubble.sent .message-time {
            text-align: right;
        }

        .message-bubble.received .message-time {
            text-align: left;
            color: #6c757d;
        }

        /* Zone de saisie */
        .chat-input-container {
            padding: 15px 20px;
            background: white;
            border-top: 1px solid #dee2e6;
            flex-shrink: 0;
        }

        .chat-input {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .chat-input textarea {
            flex: 1;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 10px 20px;
            resize: none;
            max-height: 120px;
            transition: border-color 0.2s;
        }

        .chat-input textarea:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .send-button {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .send-button:hover {
            transform: scale(1.1);
        }

        .send-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: scale(1);
        }

        /* Empty state */
        .empty-chat {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
            padding: 20px;
            text-align: center;
        }

        .empty-chat i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* Bouton nouveau message */
        .new-message-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            transition: transform 0.2s;
            z-index: 1000;
        }

        .new-message-button:hover {
            transform: scale(1.1);
        }

        .commande-badge {
            display: inline-block;
            padding: 4px 10px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            font-size: 11px;
            margin-top: 5px;
        }

        .message-sender-name {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .conversations-sidebar {
                width: 280px;
                min-width: 280px;
            }

            .conversation-avatar {
                width: 35px;
                height: 35px;
                font-size: 12px;
            }
        }

        @media (max-width: 768px) {
            .chat-main-container {
                height: calc(100vh - 120px);
            }

            .conversations-sidebar {
                width: 100%;
                max-width: 100%;
                border-right: none;
            }

            .chat-window {
                display: none;
            }

            .conversations-sidebar.hidden-mobile {
                display: none;
            }

            .chat-window.active-mobile {
                display: flex;
                position: fixed;
                top: 120px;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 1000;
            }

            .message-bubble {
                max-width: 85%;
            }

            .new-message-button {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .back-button-mobile {
                display: block !important;
            }
        }

        @media (min-width: 769px) {
            .back-button-mobile {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-0">
        <div class="chat-main-container">
            <div class="chat-container">
                <!-- Liste des conversations -->
                <div class="conversations-sidebar" id="conversationsSidebar">
                    <!-- Barre de recherche -->
                    <div class="search-box">
                        <input type="text" id="searchConversations" placeholder="🔍 Rechercher une conversation..." class="form-control-sm">
                    </div>

                    <!-- En-tête des conversations -->
                    <div class="conversations-header">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>Conversations
                            @if(auth()->user()->getNombreMessagesNonLus() > 0)
                                <span class="badge bg-danger ms-2">{{ auth()->user()->getNombreMessagesNonLus() }}</span>
                            @endif
                        </h5>
                    </div>

                    <!-- Liste scrollable des conversations -->
                    <div class="conversations-list" id="conversationsList">
                        @php
                            // Récupérer toutes les conversations (messages groupés par interlocuteur)
                            $conversations = \App\Models\Message::where(function($query) {
                                $query->where('expediteur_id', auth()->id())
                                      ->orWhere('destinataire_id', auth()->id());
                            })
                            ->with(['expediteur', 'destinataire', 'commande'])
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->groupBy(function($message) {
                                // Grouper par l'autre personne (pas moi)
                                return $message->expediteur_id == auth()->id()
                                    ? $message->destinataire_id
                                    : $message->expediteur_id;
                            });
                        @endphp

                        @forelse($conversations as $userId => $messages)
                            @php
                                $lastMessage = $messages->first();
                                $otherUser = $lastMessage->expediteur_id == auth()->id()
                                    ? $lastMessage->destinataire
                                    : $lastMessage->expediteur;
                                $unreadCount = $messages->where('destinataire_id', auth()->id())
                                    ->where('lu', false)->count();
                            @endphp

                            <div class="conversation-item {{ $unreadCount > 0 ? 'unread' : '' }}"
                                 data-user-id="{{ $otherUser->id }}"
                                 onclick="loadConversation({{ $otherUser->id }})">
                                @if($unreadCount > 0)
                                    <span class="unread-badge"></span>
                                @endif

                                <div class="d-flex align-items-center">
                                    <div class="conversation-avatar {{ $otherUser->role->value }}">
                                        {{ strtoupper(substr($otherUser->name, 0, 2)) }}
                                    </div>

                                    <div class="ms-3 flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <strong class="d-block text-truncate" style="font-size: 14px;">{{ $otherUser->name }}</strong>
                                            <small class="text-muted ms-2 flex-shrink-0" style="font-size: 11px;">
                                                {{ $lastMessage->created_at->diffForHumans(null, true) }}
                                            </small>
                                        </div>
                                        <small class="text-muted d-block text-truncate" style="font-size: 12px;">
                                            {{ Str::limit($lastMessage->contenu, 40) }}
                                        </small>
                                        @if($lastMessage->commande)
                                            <span class="badge bg-info mt-1" style="font-size: 10px;">
                                            {{ $lastMessage->commande->numero_commande }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p>Aucune conversation</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Fenêtre de chat -->
                <div class="chat-window" id="chatWindow">
                    <!-- Message par défaut -->
                    <div class="empty-chat">
                        <i class="fas fa-comments"></i>
                        <h4>Sélectionnez une conversation</h4>
                        <p class="text-muted">Choisissez une conversation à gauche pour commencer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Nouveau message -->
    <div class="modal fade" id="newMessageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-envelope me-2"></i>Nouveau message
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="destinataire_id" class="form-label">Destinataire *</label>
                            <select class="form-select" id="destinataire_id" name="destinataire_id" required>
                                <option value="">-- Sélectionner un destinataire --</option>
                                @php
                                    $users = \App\Models\User::where('id', '!=', auth()->id())
                                        ->orderBy('name')
                                        ->get();
                                @endphp
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ ucfirst($user->role->value) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sujet" class="form-label">Sujet *</label>
                            <input type="text" class="form-control" id="sujet" name="sujet" required maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label for="contenu" class="form-label">Message *</label>
                            <textarea class="form-control" id="contenu" name="contenu" rows="6" required maxlength="5000"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentConversationUserId = null;

        // Charger une conversation
        function loadConversation(userId) {
            currentConversationUserId = userId;

            // Marquer comme active
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`[data-user-id="${userId}"]`)?.classList.add('active');

            // Sur mobile, masquer la sidebar et afficher le chat
            if (window.innerWidth <= 768) {
                document.getElementById('conversationsSidebar').classList.add('hidden-mobile');
                document.getElementById('chatWindow').classList.add('active-mobile');
            }

            // Charger les messages
            fetch(`/messages/api/conversation/${userId}`)
                .then(response => response.json())
                .then(data => {
                    displayConversation(data);

                    // Marquer les messages comme lus
                    markAsRead(userId);
                })
                .catch(error => console.error('Erreur:', error));
        }

        // Retour à la liste (mobile)
        function backToList() {
            document.getElementById('conversationsSidebar').classList.remove('hidden-mobile');
            document.getElementById('chatWindow').classList.remove('active-mobile');
        }

        // Afficher la conversation
        function displayConversation(data) {
            const chatWindow = document.getElementById('chatWindow');
            const otherUser = data.otherUser;
            const messages = data.messages;

            let html = `
        <!-- En-tête du chat -->
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-light back-button-mobile me-3" onclick="backToList()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="conversation-avatar ${otherUser.role}">
                    ${otherUser.name.substring(0, 2).toUpperCase()}
                </div>
                <div class="ms-3">
                    <h5 class="mb-0" style="font-size: 16px;">${otherUser.name}</h5>
                    <small class="opacity-75">
                        <i class="fas fa-circle text-success" style="font-size: 8px;"></i>
                        ${otherUser.role.charAt(0).toUpperCase() + otherUser.role.slice(1)}
                    </small>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="chat-messages" id="chatMessages">
    `;

            messages.forEach(message => {
                const isSent = message.expediteur_id == {{ auth()->id() }};
                const bubbleClass = isSent ? 'sent' : 'received';

                html += `
            <div class="message-bubble ${bubbleClass}">
                ${!isSent ? `<div class="message-sender-name">${message.expediteur.name}</div>` : ''}
                <div class="message-content">
                    ${message.contenu.replace(/\n/g, '<br>')}
                    ${message.commande ? `<div class="commande-badge"><i class="fas fa-shopping-cart me-1"></i>${message.commande.numero_commande}</div>` : ''}
                </div>
                <div class="message-time">
                    ${formatMessageTime(message.created_at)}
                    ${isSent && message.lu ? '<i class="fas fa-check-double ms-1"></i>' : ''}
                </div>
            </div>
        `;
            });

            html += `
        </div>

        <!-- Zone de saisie -->
        <div class="chat-input-container">
            <form onsubmit="sendMessage(event)" id="messageForm">
                <div class="chat-input">
                    <textarea
                        id="messageInput"
                        placeholder="Écrivez votre message..."
                        rows="1"
                        onkeypress="handleKeyPress(event)"
                    ></textarea>
                    <button type="submit" class="send-button" id="sendButton">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    `;

            chatWindow.innerHTML = html;

            // Scroller vers le bas
            setTimeout(() => {
                const messagesContainer = document.getElementById('chatMessages');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 100);

            // Focus sur l'input
            document.getElementById('messageInput').focus();
        }

// Envoyer un message
            function sendMessage(event) {
                event.preventDefault();

                const messageInput = document.getElementById('messageInput');
                const message = messageInput.value.trim();

                if (!message || !currentConversationUserId) return;

                const sendButton = document.getElementById('sendButton');
                sendButton.disabled = true;

                fetch('/messages/send-quick', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        destinataire_id: currentConversationUserId,
                        contenu: message,
                        sujet: 'Conversation'
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageInput.value = '';
                            loadConversation(currentConversationUserId);
                        }
                    })
                    .catch(error => console.error('Erreur:', error))
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

// Marquer comme lu
            function markAsRead(userId) {
                fetch(`/messages/mark-conversation-read/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(() => {
                        // Retirer le badge non lu
                        const conversationItem = document.querySelector(`[data-user-id="${userId}"]`);
                        if (conversationItem) {
                            conversationItem.classList.remove('unread');
                            const badge = conversationItem.querySelector('.unread-badge');
                            if (badge) badge.remove();
                        }

                        // Mettre à jour le compteur global
                        updateUnreadCount();
                    });
            }

// Mettre à jour le compteur de messages non lus
            function updateUnreadCount() {
                fetch('/messages/api/nombre-non-lus')
                    .then(response => response.json())
                    .then(data => {
                        const badges = document.querySelectorAll('.sidebar .badge, .navbar .badge, .conversations-header .badge');
                        badges.forEach(badge => {
                            if (data.count > 0) {
                                badge.textContent = data.count;
                                badge.style.display = '';
                            } else {
                                badge.style.display = 'none';
                            }
                        });
                    });
            }

// Formater l'heure du message
            function formatMessageTime(datetime) {
                const date = new Date(datetime);
                const now = new Date();
                const diff = now - date;

                if (diff < 60000) return 'À l\'instant';
                if (diff < 3600000) return `Il y a ${Math.floor(diff / 60000)} min`;
                if (date.toDateString() === now.toDateString()) {
                    return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                }
                if (diff < 604800000) {
                    return date.toLocaleDateString('fr-FR', { weekday: 'short', hour: '2-digit', minute: '2-digit' });
                }
                return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' });
            }

// Recherche dans les conversations
            document.getElementById('searchConversations')?.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.conversation-item').forEach(item => {
                    const name = item.textContent.toLowerCase();
                    item.style.display = name.includes(searchTerm) ? '' : 'none';
                });
            });

// Auto-resize du textarea
            document.addEventListener('input', function(e) {
                if (e.target.id === 'messageInput') {
                    e.target.style.height = 'auto';
                    e.target.style.height = (e.target.scrollHeight) + 'px';
                }
            });

// Actualiser les conversations toutes les 30 secondes
            setInterval(() => {
                if (currentConversationUserId) {
                    loadConversation(currentConversationUserId);
                }
                updateUnreadCount();
            }, 30000);
    </script>
@endsection
