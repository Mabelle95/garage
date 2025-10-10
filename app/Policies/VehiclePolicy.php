<?php


// app/Policies/VehiclePolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isCasse();
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->isCasse() && $user->id === $vehicle->casse_id;
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->isCasse() && $user->id === $vehicle->casse_id;
    }
}
