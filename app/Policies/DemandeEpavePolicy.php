<?php


// app/Policies/DemandeEpavePolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\DemandeEpave;

class DemandeEpavePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, DemandeEpave $demande): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isClient();
    }

    public function update(User $user, DemandeEpave $demande): bool
    {
        return $user->id === $demande->user_id;
    }

    public function delete(User $user, DemandeEpave $demande): bool
    {
        return $user->id === $demande->user_id;
    }

    public function faireOffre(User $user, DemandeEpave $demande): bool
    {
        return $user->isCasse() &&
            $demande->statut === 'en_attente' &&
            !$demande->offres()->where('casse_id', $user->id)->exists();
    }

    public function accepterOffre(User $user, DemandeEpave $demande): bool
    {
        return $user->id === $demande->user_id && $demande->statut === 'en_attente';
    }
}
