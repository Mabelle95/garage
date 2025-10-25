<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications de l'utilisateur
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['lu' => true]);

        return back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('lu', false)
            ->update(['lu' => true]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * NOUVEAU : Voir une notification et rediriger vers le contenu concerné
     */
    public function view(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        // Marquer comme lue
        $notification->update(['lu' => true]);

        // Déterminer la redirection en fonction du type de notification
        $route = $this->determineRedirectRoute($notification);

        if ($route) {
            return redirect()->route($route['name'], $route['params'] ?? [])
                ->with('success', 'Redirection vers le contenu de la notification');
        }

        // Par défaut, retourner à la liste des notifications
        return redirect()->route('notifications.index')
            ->with('info', 'Aucune redirection disponible pour cette notification');
    }

    /**
     * Déterminer la route de redirection en fonction du type de notification
     */
    private function determineRedirectRoute(Notification $notification)
    {
        // Essayer de déterminer le type basé sur le titre ou le message
        $titre = strtolower($notification->titre);
        $message = strtolower($notification->message);

        // Notification de commande
        if (
            str_contains($titre, 'commande') ||
            str_contains($message, 'commande') ||
            str_contains($titre, 'n°cmd-')
        ) {
            // Extraire le numéro de commande ou l'ID si possible
            if (preg_match('/commande\s+#?(\d+)/i', $notification->message, $matches)) {
                return ['name' => 'commandes.show', 'params' => [$matches[1]]];
            }
            return ['name' => 'commandes.index'];
        }

        // Notification d'offre ou demande d'épave
        if (
            str_contains($titre, 'offre') ||
            str_contains($titre, 'demande') ||
            str_contains($titre, 'épave') ||
            str_contains($message, 'offre') ||
            str_contains($message, 'demande d\'épave')
        ) {
            // Extraire l'ID de la demande si possible
            if (preg_match('/demande\s+#?(\d+)/i', $notification->message, $matches)) {
                return ['name' => 'demandes-epaves.show', 'params' => [$matches[1]]];
            }
            return ['name' => 'demandes-epaves.index'];
        }

        // Notification de message
        if (
            str_contains($titre, 'message') ||
            str_contains($message, 'message')
        ) {
            return ['name' => 'messages.index'];
        }

        // Notification de pièce
        if (
            str_contains($titre, 'pièce') ||
            str_contains($titre, 'stock') ||
            str_contains($message, 'pièce')
        ) {
            return ['name' => 'pieces.index'];
        }

        return null;
    }
}
