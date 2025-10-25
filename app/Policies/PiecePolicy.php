<?php

namespace App\Policies;

use App\Models\Piece;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PiecePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Toutes les casses peuvent voir leurs pièces
        return $user->isCasse();
    }

    public function view(User $user, Piece $piece): bool
    {
        // Une casse ne peut voir que ses pièces
        return $user->isCasse() && $user->id === $piece->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isCasse();
    }

    public function update(User $user, Piece $piece): bool
    {
        return $user->isCasse() && $user->id === $piece->user_id;
    }

    public function delete(User $user, Piece $piece): bool
    {
        return $user->isCasse() && $user->id === $piece->user_id;
    }
}
