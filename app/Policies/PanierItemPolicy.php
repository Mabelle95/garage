<?php


// app/Policies/PanierItemPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\PanierItem;

class PanierItemPolicy
{
    public function update(User $user, PanierItem $item): bool
    {
        return $user->id === $item->panier->user_id;
    }

    public function delete(User $user, PanierItem $item): bool
    {
        return $user->id === $item->panier->user_id;
    }
}
