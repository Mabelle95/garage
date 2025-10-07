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

        // 🔹 Stats pour la casse
        $stats = [
            'vehicules' => $user->vehicles()->count(), // Si vehicles existent
            'pieces' => Piece::where('user_id', $user->id)->count(),
            'commandes_mois' => Commande::whereHas('items.piece', function($query) use($user) {
                $query->where('user_id', $user->id);
            })->whereMonth('created_at', now()->month)->count(),
            'chiffre_affaires_mois' => Commande::whereHas('items.piece', function($query) use($user) {
                $query->where('user_id', $user->id);
            })->whereMonth('created_at', now()->month)->sum('total'),
        ];

        // 🔹 Pièces récentes pour la casse
        $recentPieces = Piece::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 🔹 Véhicules récents pour info (si tu gardes vehicles)
        $recentVehicles = $user->vehicles()->latest()->take(5)->get();

        // 🔹 Commandes récentes
        $recentCommandes = Commande::whereHas('items.piece', function($query) use($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['client', 'items.piece'])
            ->latest()
            ->take(5)
            ->get();

        // 🔹 Demandes épaves récentes
        $demandesEpaves = DemandeEpave::where('statut', 'en_attente')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.casse', compact(
            'stats',
            'recentPieces',
            'recentVehicles',
            'recentCommandes',
            'demandesEpaves'
        ));
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

        $recentCommandes = $user->commandes()
            ->with(['items.piece'])
            ->latest()
            ->take(5)
            ->get();

        $notifications = $user->notifications()->where('lu', false)->latest()->take(5)->get();

        $piecesPopulaires = Piece::where('disponible', true)
            ->withCount('commandeItems')
            ->orderBy('commande_items_count', 'desc')
            ->take(6)
            ->get();

        return view('dashboard.client', compact(
            'stats',
            'recentCommandes',
            'notifications',
            'piecesPopulaires'
        ));
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
        $recentCommandes = Commande::with(['client', 'items.piece'])->latest()->take(5)->get();

        $commandes = DB::table('commandes')
            ->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Transformer les données pour Chart.js
        $labels_chart = [];
        $data = [];
        foreach ($commandes as $commande) {
            $labels_chart[] = date("F", mktime(0, 0, 0, $commande->mois, 1));
            $data[] = $commande->total;
        }

        $clientsInscrits = DB::table('users')
            ->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->where('role', 'client')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        $dataClients = [];
        foreach ($clientsInscrits as $cli) {
            $dataClients[] = $cli->total;
        }

        return view('dashboard.admin', compact(
            'stats',
            'commandesParMois',
            'recentUsers',
            'recentCommandes',
            'data',
            'labels_chart',
            'dataClients'
        ));
    }
}
