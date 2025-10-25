<?php

namespace App\Http\Controllers\Auth; // Namespace correct

use App\Enums\UserRole;
use App\Mail\CasseApprovedMail;
use App\Mail\CasseRejectedMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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




    /**
     * Liste des casses en attente d'approbation
     */
    public function pendingCasses()
    {
        $pendingCasses = User::where('role', UserRole::CASSE)
            ->where('approved', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.casses.pending', compact('pendingCasses'));
    }

    /**
     * Approuver une casse
     */
    public function approveCasse($id)
    {
        $casse = User::findOrFail($id);

        if (!$casse->isCasse()) {
            return back()->with('error', 'Cet utilisateur n\'est pas une casse.');
        }

        $casse->update([
            'approved' => true,
            'approved_at' => now(),
        ]);

        // Envoyer un email de notification à la casse
        Mail::to($casse->email)->send(new CasseApprovedMail($casse));

        return back()->with('success', "La casse {$casse->name} a été approuvée avec succès.");
    }

    /**
     * Rejeter une casse
     */
    public function rejectCasse($id)
    {
        $casse = User::findOrFail($id);

        if (!$casse->isCasse()) {
            return back()->with('error', 'Cet utilisateur n\'est pas une casse.');
        }

        // Envoyer un email de notification de rejet avant la suppression
        Mail::to($casse->email)->send(new CasseRejectedMail($casse));

        // Supprimer le compte
        $casse->delete();

        return back()->with('success', "La casse {$casse->name} a été rejetée et le compte supprimé.");
    }
}
