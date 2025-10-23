@extends('layouts.app')

@section('title', 'Administration - Messagerie')

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

        /* Notice admin fixe en haut */
        .admin-notice {
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
            color: white;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            flex-shrink: 0;
        }

        /* Container du chat */
        .chat-container {
            height: 100%;
            display: flex;
            overflow: hidden;
        }

        /* Liste des conversations */
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

        /* Stats card */
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .stats-card h6 {
            margin: 0;
            font-size: 12px;
            opacity: 0.9;
        }

        .stats-card h3 {
            margin: 5px 0 0 0;
            font-size: 24px;
            font-weight: bold;
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

        .conversation-avatar.admin {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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

        /* Footer du chat */
        .chat-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            flex-shrink: 0;
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

        /* Badges */
        .admin-badge {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
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

        .conversation-participants {
            font-size: 10px;
            color: #6c757d;
            margin-top: 3px;
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
                top: 140px;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 1000;
            }

            .message-bubble {
                max-width: 85%;
            }

            .admin-notice {
                font-size: 12px;
                padding: 8px 15px;
            }

            .admin-notice h5 {
                font-size: 14px;
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
        <!-- Notice Admin -->
        <div class="admin-notice">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Mode Administrateur - Messagerie
                    </h5>
                    <small class="d-none d-md-block">Vous pouvez consulter toutes les conversations en lecture seule</small>
                </div>
                <span class="admin-badge">
                <i class="fas fa-eye me-1"></i>LECTURE SEULE
            </span>
            </div>
        </div>

        <div class="chat-main-container">
            <div class="chat-container">
                <!-- Liste des conversations -->
                <div class="conversations-sidebar" id="conversationsSidebar">
                    <!-- Header avec stats -->
                    <div class="conversations-header">
                        <div class="stats-card">
                            <h6>Total des conversations</h6>
                            <h3>{{ $totalConversations }}</h3>
                        </div>
                    </div>

                    <!-- Barre de recherche -->
                    <div class="search-box">
                        <input type="text" id="searchConversations" placeholder="🔍 Rechercher..." class="form-control-sm">
                    </div>

                    <!-- Liste scrollable -->
                    <div class="conversations-list" id="conversationsList">
                        @forelse($conversations as $conversation)
                            @php
                                $lastMessage = $conversation['lastMessage'];
                                $user1 = $conversation['user1'];
                                $user2 = $conversation['user2'];
                            @endphp

                            <div class="conversation-item"
                                 data-user1-id="{{ $user1->id }}"
                                 data-user2-id="{{ $user2->id }}"
                                 onclick="loadConversation({{ $user1->id }}, {{ $user2->id }})">

                                <div class="d-flex align-items-start">
                                    <div class="d-flex flex-column me-2">
                                        <div class="conversation-avatar {{ $user1->role->value }}" style="margin-bottom: 5px;">
                                            {{ strtoupper(substr($user1->name, 0, 2)) }}
                                        </div>
                                        <div class="conversation-avatar {{ $user2->role->value }}">
                                            {{ strtoupper(substr($user2->name, 0, 2)) }}
                                        </div>
                                    </div>

                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <strong class="d-block text-truncate" style="font-size: 14px;">{{ $user1->name }}</strong>
                                                <small class="text-muted" style="font-size: 12px;">↔️ {{ $user2->name }}</small>
                                            </div>
                                            <small class="text-muted ms-2 flex-shrink-0" style="font-size: 10px;">
                                                {{ $lastMessage->created_at->diffForHumans(null, true) }}
                                            </small>
                                        </div>

                                        <div class="conversation-participants">
                                        <span class="badge bg-{{ $user1->role->value === 'casse' ? 'danger' : 'primary' }}" style="font-size: 9px;">
                                            {{ ucfirst($user1->role->value) }}
                                        </span>
                                            <span class="badge bg-{{ $user2->role->value === 'casse' ? 'danger' : 'primary' }}" style="font-size: 9px;">
                                            {{ ucfirst($user2->role->value) }}
                                        </span>
                                        </div>

                                        <small class="text-muted d-block text-truncate mt-1" style="font-size: 11px;">
                                            <i class="fas fa-comment-dots me-1"></i>
                                            {{ Str::limit($lastMessage->contenu, 35) }}
                                        </small>

                                        @if($lastMessage->commande)
                                            <span class="badge bg-info mt-1" style="font-size: 9px;">
                                            <i class="fas fa-shopping-cart me-1"></i>
                                            {{ $lastMessage->commande->numero_commande }}
                                        </span>
                                        @endif

                                        <div class="mt-1">
                                        <span class="badge bg-secondary" style="font-size: 9px;">
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ $conversation['messageCount'] }} msg
                                        </span>
                                        </div>
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
                        <p class="text-muted">Choisissez une conversation à gauche pour consulter les messages</p>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Mode lecture seule :</strong> Vous pouvez uniquement consulter les conversations
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentConversationUser1Id = null;
        let currentConversationUser2Id = null;

        // Charger une conversation
        function loadConversation(user1Id, user2Id) {
            currentConversationUser1Id = user1Id;
            currentConversationUser2Id = user2Id;

            // Marquer comme active
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`[data-user1-id="${user1Id}"][data-user2-id="${user2Id}"]`)?.classList.add('active');

            // Sur mobile, masquer la sidebar et afficher le chat
            if (window.innerWidth <= 768) {
                document.getElementById('conversationsSidebar').classList.add('hidden-mobile');
                document.getElementById('chatWindow').classList.add('active-mobile');
            }

            // Charger les messages
            fetch(`/admin/messages/conversation/${user1Id}/${user2Id}`)
                .then(response => response.json())
                .then(data => {
                    displayConversation(data);
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
            const user1 = data.user1;
            const user2 = data.user2;
            const messages = data.messages;

            let html = `
        <!-- En-tête du chat -->
            <div class="chat-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
            <button class="btn btn-sm btn-light back-button-mobile me-3" onclick="backToList()">
            <i class="fas fa-arrow-left"></i>
            </button>
            <div class="d-flex align-items-center flex-grow-1">
            <div class="conversation-avatar ${user1.role}">
            ${user1.name.substring(0, 2).toUpperCase()}
            </div>
            <div class="ms-2 me-2">
            <h6 class="mb-0" style="font-size: 14px;">${user1.name}</h6>
            <small class="opacity-75" style="font-size: 11px;">${user1.role.charAt(0).toUpperCase() + user1.role.slice(1)}</small>
            </div>

            <i class="fas fa-exchange-alt mx-2"></i>

            <div class="conversation-avatar ${user2.role}">
            ${user2.name.substring(0, 2).toUpperCase()}
            </div>
            <div class="ms-2">
            <h6 class="mb-0" style="font-size: 14px;">${user2.name}</h6>
            <small class="opacity-75" style="font-size: 11px;">${user2.role.charAt(0).toUpperCase() + user2.role.slice(1)}</small>
            </div>
            </div>
            <span class="admin-badge d-none d-md-inline">
            <i class="fas fa-eye me-1"></i>LECTURE SEULE
            </span>
            </div>
            </div>

                <!-- Messages -->
            <div class="chat-messages" id="chatMessages">
            `;

            if (messages.length === 0) {
            html += `
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                <p>Aucun message dans cette conversation</p>
            </div>
        `;
            } else {
            messages.forEach(message => {
            const isSentByUser1 = message.expediteur_id == user1.id;
            const bubbleClass = isSentByUser1 ? 'sent' : 'received';
            const sender = isSentByUser1 ? user1 : user2;

            html += `
                <div class="message-bubble ${bubbleClass}">
                    <div class="message-sender-name">
                        ${sender.name} (${sender.role.charAt(0).toUpperCase() + sender.role.slice(1)})
                    </div>
                    <div class="message-content">
                        ${message.contenu.replace(/\n/g, '<br>')}
                        ${message.commande ? `<div class="commande-badge"><i class="fas fa-shopping-cart me-1"></i>${message.commande.numero_commande}</div>` : ''}
                    </div>
                    <div class="message-time">
                        ${formatMessageTime(message.created_at)}
                        ${message.lu ? '<i class="fas fa-check-double ms-1"></i>' : '<i class="fas fa-check ms-1"></i>'}
                    </div>
                </div>
            `;
            });
            }

            html += `
            </div>

                <!-- Footer -->
            <div class="chat-footer">
            <div class="alert alert-warning mb-0">
            <i class="fas fa-lock me-2"></i>
            <strong>Mode Administrateur :</strong> Lecture seule uniquement
            </div>
            </div>
            `;

    chatWindow.innerHTML = html;

    // Scroller vers le bas
    setTimeout(() => {
        const messagesContainer = document.getElementById('chatMessages');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }, 100);
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

// Actualiser les conversations toutes les 60 secondes
setInterval(() => {
    if (currentConversationUser1Id && currentConversationUser2Id) {
        loadConversation(currentConversationUser1Id, currentConversationUser2Id);
    }
}, 60000);
</script>
@endsection
