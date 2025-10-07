<?php

namespace App\Http\Controllers\Auth; // Namespace correct

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
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

    /**
     * Liste paginée des utilisateurs
     */
    public function users()
    {
        // Récupération des casses et clients pour la vue
        $casses = User::where('role', 'casse')
            ->with(['vehicles', 'pieces'])
            ->get();

        $clients = User::where('role', 'client')
            ->with(['commandes.items.piece.vehicle.casse'])
            ->get();

        $users = User::with(['vehicles', 'commandes.items.piece.vehicle.casse'])
            ->paginate(20);

        return view('admin.users.index', compact('users', 'casses', 'clients'));
    }

    /**
     * Statistiques admin
     */
    public function statistics()
    {
        return view('admin.statistics');
    }

    /**
     * Paramètres admin
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Supprimer les relations associées si nécessaire
        // ex: $user->vehicles()->delete();

        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
