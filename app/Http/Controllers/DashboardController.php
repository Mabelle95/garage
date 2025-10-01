<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Piece;
use App\Models\Commande;
use App\Models\DemandeEpave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isCasse()) {
            return $this->casseDashboard();
        } elseif ($user->isClient()) {
            return $this->clientDashboard();
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard();
        }
    }

    private function casseDashboard()
    {
        $user = Auth::user();

        $stats = [
            'vehicules' => $user->vehicles()->count(),
            'pieces' => Piece::whereHas('vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })->count(),
            'commandes_mois' => Commande::whereHas('items.piece.vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })->whereMonth('created_at', now()->month)->count(),
            'chiffre_affaires_mois' => Commande::whereHas('items.piece.vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })->whereMonth('created_at', now()->month)->sum('total')
        ];

        $recentVehicles = $user->vehicles()->latest()->take(5)->get();

        // Correction: utiliser 'client' au lieu de 'user'
        $recentCommandes = Commande::whereHas('items.piece.vehicle', function($query) use($user) {
            $query->where('casse_id', $user->id);
        })
        ->with(['client', 'items.piece.vehicle.casse'])
        ->latest()->take(5)->get();

        $demandesEpaves = DemandeEpave::where('statut', 'en_attente')
            ->latest()->take(5)->get();

        return view('dashboard.casse', compact('stats', 'recentVehicles', 'recentCommandes', 'demandesEpaves'));
    }

    private function clientDashboard()
    {
        $user = Auth::user();

        $stats = [
            'commandes_total' => $user->commandes()->count(),
            'commandes_en_cours' => $user->commandes()
                ->whereIn('statut', ['en_attente', 'confirmee', 'en_preparation', 'expedie'])
                ->count(),
            'panier_items' => $user->panier->items()->count(),
            'favoris' => $user->favoris()->count()
        ];

        $recentCommandes = $user->commandes()->with(['items.piece.vehicle.casse'])->latest()->take(5)->get();
        $notifications = $user->notifications()->where('lu', false)->latest()->take(5)->get();

        $piecesPopulaires = Piece::where('disponible', true)
            ->withCount('commandeItems')
            ->orderBy('commande_items_count', 'desc')
            ->take(6)->get();

        return view('dashboard.client', compact('stats', 'recentCommandes', 'notifications', 'piecesPopulaires'));
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_casses' => User::where('role', 'casse')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_vehicles' => Vehicle::count(),
            'total_pieces' => Piece::count(),
            'total_commandes' => Commande::count(),
            'chiffre_affaires' => Commande::where('statut_paiement', 'paye')->sum('total')
        ];

        $commandesParMois = Commande::select(
            DB::raw('MONTH(created_at) as mois'),
            DB::raw('COUNT(*) as total')
        )->whereYear('created_at', now()->year)
            ->groupBy('mois')
            ->get();

        $recentUsers = User::latest()->take(5)->get();
        $recentCommandes = Commande::with(['client', 'items.piece.vehicle.casse'])->latest()->take(5)->get();

        return view('dashboard.admin', compact('stats', 'commandesParMois', 'recentUsers', 'recentCommandes'));
    }
}
