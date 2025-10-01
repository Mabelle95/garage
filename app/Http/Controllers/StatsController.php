<?php


// app/Http/Controllers/StatsController.php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Piece;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isCasse()) {
            return $this->cassesStats();
        } elseif ($user->isAdmin()) {
            return $this->adminStats();
        }

        abort(403);
    }

    private function cassesStats()
    {
        $user = Auth::user();

        // Statistiques générales
        $stats = [
            'vehicules_total' => $user->vehicles()->count(),
            'pieces_total' => Piece::whereHas('vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })->count(),
            'commandes_total' => Commande::whereHas('items.piece.vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })->count(),
            'chiffre_affaires_total' => Commande::whereHas('items.piece.vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })->where('statut_paiement', 'paye')->sum('total')
        ];

        // Évolution des ventes par mois (12 derniers mois)
        $ventesParMois = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $ventes = Commande::whereHas('items.piece.vehicle', function($query) use($user) {
                $query->where('casse_id', $user->id);
            })
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('statut_paiement', 'paye')
                ->sum('total');

            $ventesParMois[] = [
                'mois' => $date->format('M Y'),
                'ventes' => (float) $ventes
            ];
        }

        // Top des pièces vendues
        $topPieces = Piece::whereHas('vehicle', function($query) use($user) {
            $query->where('casse_id', $user->id);
        })
            ->withCount('commandeItems')
            ->orderBy('commande_items_count', 'desc')
            ->take(10)
            ->get();

        return view('stats.casse', compact('stats', 'ventesParMois', 'topPieces'));
    }

    private function adminStats()
    {
        // Statistiques globales
        $stats = [
            'users_total' => User::count(),
            'casses_actives' => User::where('role', 'casse')->where('actif', true)->count(),
            'clients_total' => User::where('role', 'client')->count(),
            'vehicules_total' => Vehicle::count(),
            'pieces_total' => Piece::count(),
            'commandes_total' => Commande::count(),
            'ca_total' => Commande::where('statut_paiement', 'paye')->sum('total')
        ];

        // Évolution des inscriptions
        $inscriptionsParMois = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $inscriptions = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $inscriptionsParMois[] = [
                'mois' => $date->format('M Y'),
                'inscriptions' => $inscriptions
            ];
        }

        // Top des casses par CA
        $topCasses = User::where('role', 'casse')
            ->withSum(['vehicles as ca_total' => function($query) {
                $query->join('pieces', 'vehicles.id', '=', 'pieces.vehicle_id')
                    ->join('commande_items', 'pieces.id', '=', 'commande_items.piece_id')
                    ->join('commandes', 'commande_items.commande_id', '=', 'commandes.id')
                    ->where('commandes.statut_paiement', 'paye');
            }], 'commande_items.prix_unitaire')
            ->orderBy('ca_total', 'desc')
            ->take(10)
            ->get();

        return view('stats.admin', compact('stats', 'inscriptionsParMois', 'topCasses'));
    }
}
