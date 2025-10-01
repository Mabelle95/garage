<?php


// app/Http/Controllers/Api/StatsController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Piece;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->isCasse()) {
            return $this->casseDashboardStats();
        } elseif ($user->isClient()) {
            return $this->clientDashboardStats();
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    private function casseDashboardStats()
    {
        $user = Auth::user();

        $stats = [
            'vehicles_total' => $user->vehicles()->count(),
            'pieces_total' => Piece::whereHas('vehicle', function ($query) use ($user) {
                $query->where('casse_id', $user->id);
            })->count(),
            'commandes_mois' => Commande::whereHas('items.piece.vehicle', function ($query) use ($user) {
                $query->where('casse_id', $user->id);
            })->whereMonth('created_at', now()->month)->count(),
            'chiffre_affaires_mois' => Commande::whereHas('items.piece.vehicle', function ($query) use ($user) {
                $query->where('casse_id', $user->id);
            })->whereMonth('created_at', now()->month)->sum('total')
        ];

        // Évolution des ventes sur 12 mois
        $ventesParMois = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $ventes = Commande::whereHas('items.piece.vehicle', function ($query) use ($user) {
                $query->where('casse_id', $user->id);
            })
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');

            $ventesParMois[] = [
                'mois' => $date->format('M Y'),
                'total' => (float)$ventes
            ];
        }

        return response()->json([
            'stats' => $stats,
            'ventes_par_mois' => $ventesParMois
        ]);
    }

    private function clientDashboardStats()
    {
        $user = Auth::user();

        $stats = [
            'commandes_total' => $user->commandes()->count(),
            'commandes_en_cours' => $user->commandes()
                ->whereIn('statut', ['en_attente', 'confirmee', 'en_preparation', 'expedie'])
                ->count(),
            'montant_total_depense' => $user->commandes()
                ->where('statut_paiement', 'paye')
                ->sum('total'),
            'pieces_favorites' => $user->favoris()
                ->where('favori_type', 'App\Models\Piece')
                ->count()
        ];

        return response()->json(['stats' => $stats]);
    }
}
