<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Commande;

class CommandePolicy
{
    /**
     * Détermine si l'utilisateur peut voir toutes les commandes.
     */
    public function viewAny(User $user): bool
    {
        return $user->isClient() || $user->isCasse();
    }

    /**
     * Détermine si l'utilisateur peut voir une commande spécifique.
     */
    public function view(User $user, Commande $commande): bool
    {
        // Le client propriétaire peut toujours voir sa commande
        if ($user->isClient() && $user->id === $commande->user_id) {
            return true;
        }

        // La casse peut voir toutes les commandes (ou filtrer selon une logique métier)
        if ($user->isCasse()) {
            return true; // Si tu veux filtrer par disponibilité de la casse, il faut une autre relation
        }

        return false;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour le statut d'une commande.
     */
    public function updateStatut(User $user, Commande $commande): bool
    {
        // Seule la casse peut modifier le statut
        return $user->isCasse();
    }

    /**
     * Détermine si l'utilisateur peut annuler une commande.
     */
    public function annuler(User $user, Commande $commande): bool
    {
        return $user->isClient() &&
               $user->id === $commande->user_id &&
               in_array($commande->statut, ['en_attente', 'confirmee']);
    }
}
