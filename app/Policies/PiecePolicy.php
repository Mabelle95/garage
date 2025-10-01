<?php


// app/Policies/PiecePolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Piece;

class PiecePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Piece $piece): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isCasse();
    }

    public function update(User $user, Piece $piece): bool
    {
        return $user->isCasse() && $user->id === $piece->vehicle->casse_id;
    }

    public function delete(User $user, Piece $piece): bool
    {
        return $user->isCasse() && $user->id === $piece->vehicle->casse_id;
    }
}
