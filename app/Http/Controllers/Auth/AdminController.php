<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        // Toutes les casses avec leurs véhicules et pièces
        $casses = User::where('role', 'casse')
            ->with(['vehicles', 'pieces'])
            ->get();

        // Tous les clients avec leurs commandes et pièces commandées
        $clients = User::where('role', 'client')
            ->with(['commandes.items.piece.vehicle.casse'])
            ->get();

        return view('admin.dashboard', compact('casses', 'clients'));
    }

    // Liste paginée des utilisateurs
    public function users()
    {
        $users = User::with(['vehicles', 'commandes.items.piece.vehicle.casse'])
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    // Statistiques admin
    public function statistics()
    {
        return view('admin.statistics');
    }

    // Paramètres admin
    public function settings()
    {
        return view('admin.settings');
    }
}
