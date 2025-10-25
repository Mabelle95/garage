<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Afficher l'interface de chat
     */
    public function index()
    {
        return view('messages.index');
    }

    /**
     * Charger une conversation avec un utilisateur
     */

    public function getConversation($userId)
    {
        $otherUser = User::findOrFail($userId);

        // Récupérer tous les messages entre les deux utilisateurs
        $messages = Message::where(function($query) use ($userId) {
            $query->where('expediteur_id', Auth::id())
                ->where('destinataire_id', $userId);
        })
            ->orWhere(function($query) use ($userId) {
                $query->where('expediteur_id', $userId)
                    ->where('destinataire_id', Auth::id());
            })
            ->with(['expediteur', 'destinataire', 'commande'])
            ->orderBy('created_at', 'asc')
            ->get();


        return response()->json([
            'otherUser' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'email' => $otherUser->email,
                'role' => $otherUser->role->value,
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
     * Envoyer un message rapide (pour le chat)
     */
    public function sendQuick(Request $request)
    {
        $validated = $request->validate([
            'destinataire_id' => 'required|exists:users,id',
            'contenu' => 'required|string|max:5000',
            'sujet' => 'nullable|string|max:255',
            'commande_id' => 'nullable|exists:commandes,id',
        ]);

        $message = Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $validated['destinataire_id'],
            'commande_id' => $validated['commande_id'] ?? null,
            'sujet' => $validated['sujet'] ?? 'Conversation',
            'contenu' => $validated['contenu'],
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load(['expediteur', 'destinataire', 'commande']),
        ]);
    }

    /**
     * Marquer tous les messages d'une conversation comme lus
     */
    public function markConversationAsRead($userId)
    {
        Message::where('expediteur_id', $userId)
            ->where('destinataire_id', Auth::id())
            ->where('lu', false)
            ->update([
                'lu' => true,
                'lu_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Afficher un message (ancienne version - gardée pour compatibilité)
     */
    public function show(Message $message)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est concerné par ce message
        if ($message->expediteur_id !== $user->id && $message->destinataire_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        // Marquer comme lu si c'est le destinataire
        if ($message->destinataire_id === $user->id) {
            $message->marquerCommeLu();
        }

        return view('messages.show', compact('message'));
    }

    /**
     * Formulaire de création de message
     */
    public function create(Request $request)
    {
        $commandeId = $request->query('commande_id');
        $destinataireId = $request->query('destinataire_id');

        $commande = null;
        $destinataire = null;

        if ($commandeId) {
            $commande = Commande::findOrFail($commandeId);
            // Déterminer le destinataire selon le rôle
            if (Auth::user()->isClient()) {
                $destinataire = $commande->casse;
            } else {
                $destinataire = $commande->client;
            }
        } elseif ($destinataireId) {
            $destinataire = User::findOrFail($destinataireId);
        }

        return view('messages.create', compact('commande', 'destinataire'));
    }

    /**
     * Enregistrer un nouveau message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destinataire_id' => 'required|exists:users,id',
            'commande_id' => 'nullable|exists:commandes,id',
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $validated['destinataire_id'],
            'commande_id' => $validated['commande_id'] ?? null,
            'sujet' => $validated['sujet'],
            'contenu' => $validated['contenu'],
        ]);

        return redirect()->route('messages.index')
            ->with('success', 'Message envoyé avec succès');
    }

    /**
     * Envoyer un message rapide depuis une commande (AJAX)
     */
    public function envoyerRapide(Request $request)
    {
        $validated = $request->validate([
            'destinataire_id' => 'required|exists:users,id',
            'commande_id' => 'required|exists:commandes,id',
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $validated['destinataire_id'],
            'commande_id' => $validated['commande_id'],
            'sujet' => $validated['sujet'],
            'contenu' => $validated['contenu'],
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message envoyé avec succès',
            ]);
        }

        return redirect()->back()->with('success', 'Message envoyé avec succès');
    }

    /**
     * Marquer un message comme lu
     */
    public function marquerCommeLu(Message $message)
    {
        if ($message->destinataire_id !== Auth::id()) {
            abort(403);
        }

        $message->marquerCommeLu();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer tous les messages comme lus
     */
    public function marquerTousCommeLus()
    {
        Message::where('destinataire_id', Auth::id())
            ->where('lu', false)
            ->update([
                'lu' => true,
                'lu_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Tous les messages ont été marqués comme lus');
    }

    /**
     * Supprimer un message
     */
    public function destroy(Message $message)
    {
        // Seul le destinataire peut supprimer un message reçu
        if ($message->destinataire_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('messages.index')
            ->with('success', 'Message supprimé avec succès');
    }

    /**
     * Obtenir le nombre de messages non lus
     */
    public function getNombreNonLus()
    {
        $count = Message::where('destinataire_id', Auth::id())
            ->where('lu', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function showConversation($userId)
    {
        // Appel de ta fonction existante
        $response = $this->getConversation($userId);
        $data = $response->getData(true); // Convertit le JSON en tableau PHP

        return view('messages.conversation', [
            'conversation' => $data,
            'userId' => $userId
        ]);
    }

}
