<?php


// app/Policies/CommandePolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Commande;

class CommandePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role == "client" || $user->role == "casse";
    }

    public function view(User $user, Commande $commande): bool
    {
        // Le client propriétaire ou les casses concernées peuvent voir
        if ($user->id === $commande->user_id || $user->role === "casse") {
            return true;
        }

        if ($user->isCasse()) {
            return $commande->items()->whereHas('piece.vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })->exists();
        }
        else{
            return false;
        }

    }

    public function updateStatut(User $user, Commande $commande): bool
    {
        return $user->isCasse() && $commande->items()->whereHas('piece.vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })->exists();
    }

    public function annuler(User $user, Commande $commande): bool
    {
        return $user->id === $commande->user_id &&
            in_array($commande->statut, ['en_attente', 'confirmee']);
    }
}
