<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMessageController extends Controller
{
    /**
     * Afficher toutes les conversations (admin uniquement)
     */
    public function index()
    {
        // Récupérer toutes les conversations groupées par paires d'utilisateurs
        $allMessages = Message::with(['expediteur', 'destinataire', 'commande'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Grouper les messages par paire d'utilisateurs (conversation unique)
        $conversationsMap = [];

        foreach ($allMessages as $message) {
            // Créer une clé unique pour chaque paire d'utilisateurs
            $users = [$message->expediteur_id, $message->destinataire_id];
            sort($users); // Trier pour avoir toujours la même clé peu importe le sens
            $conversationKey = implode('-', $users);

            if (!isset($conversationsMap[$conversationKey])) {
                $conversationsMap[$conversationKey] = [
                    'user1' => $message->expediteur_id == $users[0] ? $message->expediteur : $message->destinataire,
                    'user2' => $message->expediteur_id == $users[1] ? $message->expediteur : $message->destinataire,
                    'lastMessage' => $message,
                    'messageCount' => 0,
                ];
            }

            $conversationsMap[$conversationKey]['messageCount']++;
        }

        // Convertir en collection et trier par date du dernier message
        $conversations = collect($conversationsMap)->sortByDesc(function($conversation) {
            return $conversation['lastMessage']->created_at;
        })->values();

        $totalConversations = $conversations->count();

        return view('admin.messages.index', compact('conversations', 'totalConversations'));
    }

    /**
     * Récupérer une conversation entre deux utilisateurs
     */
    public function getConversation($user1Id, $user2Id)
    {
        $user1 = User::findOrFail($user1Id);
        $user2 = User::findOrFail($user2Id);

        // Récupérer tous les messages entre les deux utilisateurs
        $messages = Message::where(function($query) use ($user1Id, $user2Id) {
            $query->where('expediteur_id', $user1Id)
                ->where('destinataire_id', $user2Id);
        })
            ->orWhere(function($query) use ($user1Id, $user2Id) {
                $query->where('expediteur_id', $user2Id)
                    ->where('destinataire_id', $user1Id);
            })
            ->with(['expediteur', 'destinataire', 'commande'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'user1' => [
                'id' => $user1->id,
                'name' => $user1->name,
                'email' => $user1->email,
                'role' => $user1->role->value,
            ],
            'user2' => [
                'id' => $user2->id,
                'name' => $user2->name,
                'email' => $user2->email,
                'role' => $user2->role->value,
            ],
            'messages' => $messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'expediteur_id' => $message->expediteur_id,
                    'destinataire_id' => $message->destinataire_id,
                    'sujet' => $message->sujet,
                    'contenu' => $message->contenu,
                    'lu' => $message->lu,
                    'created_at' => $message->created_at->toISOString(),
                    'expediteur' => [
                        'id' => $message->expediteur->id,
                        'name' => $message->expediteur->name,
                    ],
                    'commande' => $message->commande ? [
                        'id' => $message->commande->id,
                        'numero_commande' => $message->commande->numero_commande,
                    ] : null,
                ];
            }),
        ]);
    }

    /**
     * Obtenir les statistiques des messages
     */
    public function statistics()
    {
        $stats = [
            'total_messages' => Message::count(),
            'total_conversations' => $this->countUniqueConversations(),
            'messages_non_lus' => Message::where('lu', false)->count(),
            'messages_today' => Message::whereDate('created_at', today())->count(),
            'messages_this_week' => Message::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'messages_with_commande' => Message::whereNotNull('commande_id')->count(),
            'most_active_users' => $this->getMostActiveUsers(),
        ];

        return response()->json($stats);
    }

    /**
     * Compter le nombre de conversations uniques
     */
    private function countUniqueConversations()
    {
        $messages = Message::select('expediteur_id', 'destinataire_id')->get();

        $conversations = [];
        foreach ($messages as $message) {
            $users = [$message->expediteur_id, $message->destinataire_id];
            sort($users);
            $key = implode('-', $users);
            $conversations[$key] = true;
        }

        return count($conversations);
    }

    /**
     * Obtenir les utilisateurs les plus actifs
     */
    private function getMostActiveUsers()
    {
        $userMessages = DB::table('messages')
            ->select('users.id', 'users.name', DB::raw('COUNT(*) as message_count'))
            ->join('users', function($join) {
                $join->on('messages.expediteur_id', '=', 'users.id')
                    ->orOn('messages.destinataire_id', '=', 'users.id');
            })
            ->groupBy('users.id', 'users.name')
            ->orderBy('message_count', 'desc')
            ->limit(5)
            ->get();

        return $userMessages;
    }

    /**
     * Rechercher dans les messages
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $messages = Message::where('sujet', 'LIKE', "%{$query}%")
            ->orWhere('contenu', 'LIKE', "%{$query}%")
            ->with(['expediteur', 'destinataire', 'commande'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($messages);
    }
}
