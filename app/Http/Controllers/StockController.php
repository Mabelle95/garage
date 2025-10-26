<?php

namespace App\Http\Controllers;

use App\Models\DemandeEpave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Afficher la page de gestion des stocks (pièces et épaves)
     */
    public function index()
    {
        $user = Auth::user();

        // ========================================
        // DONNÉES POUR LES PIÈCES
        // ========================================
        $pieces = $user->pieces()->get();

        // Statistiques pièces
        $totalPieces = $pieces->count();
        $totalStock = $pieces->sum('quantite');
        $piecesDisponibles = $pieces->where('disponible', true)->count();

        // Alertes de stock
        $stockFaible = $pieces->where('quantite', '>', 0)->where('quantite', '<=', 3);
        $stockVide = $pieces->where('quantite', 0);

        // ========================================
        // DONNÉES POUR LES DEMANDES D'ÉPAVES
        // ========================================
        // Récupérer les demandes de l'utilisateur connecté
        $mesDemandes = DemandeEpave::where('user_id', $user->id)
            ->with(['offres'])
            ->latest()
            ->paginate(10, ['*'], 'mes_demandes');

        // Statistiques des épaves
        $statsEpaves = [
            'vehicules' => DemandeEpave::where('user_id', $user->id)
                ->where('type', 'vehicule')
                ->count(),
            'epaves' => DemandeEpave::where('user_id', $user->id)
                ->where('type', 'epave')
                ->count(),
            'en_attente' => DemandeEpave::where('user_id', $user->id)
                ->where('statut', 'en_attente')
                ->count(),
            'vendus' => DemandeEpave::where('user_id', $user->id)
                ->where('statut', 'vendu')
                ->count(),
        ];

        return view('gestion.stocks', compact(
            'pieces',
            'totalPieces',
            'totalStock',
            'piecesDisponibles',
            'stockFaible',
            'stockVide',
            'mesDemandes',
            'statsEpaves'
        ));
    }

    /**
     * Statistiques détaillées des stocks
     */
    public function statistiques()
    {
        $user = Auth::user();

        // Statistiques avancées pour les pièces
        $pieceStats = [
            'valeur_totale' => $user->pieces()->sum(\DB::raw('prix * quantite')),
            'pieces_epuisees' => $user->pieces()->where('quantite', 0)->count(),
            'pieces_faible_stock' => $user->pieces()
                ->where('quantite', '>', 0)
                ->where('quantite', '<=', 3)
                ->count(),
            'categories' => $user->pieces()
                ->select('nom', \DB::raw('COUNT(*) as total'))
                ->groupBy('nom')
                ->orderByDesc('total')
                ->take(10)
                ->get(),
        ];

        // Statistiques avancées pour les épaves
        $epaveStats = [
            'valeur_offres_recues' => DemandeEpave::where('user_id', $user->id)
                ->whereHas('offres')
                ->with('offres')
                ->get()
                ->sum(function ($demande) {
                    return $demande->offres->sum('prix_offert');
                }),
            'taux_conversion' => $this->calculerTauxConversion($user->id),
            'delai_moyen_vente' => $this->calculerDelaiMoyenVente($user->id),
        ];

        return view('gestion.stocks.statistiques', compact('pieceStats', 'epaveStats'));
    }

    /**
     * Calculer le taux de conversion des demandes en ventes
     */
    private function calculerTauxConversion($userId)
    {
        $totalDemandes = DemandeEpave::where('user_id', $userId)->count();
        $demandesVendues = DemandeEpave::where('user_id', $userId)
            ->where('statut', 'vendu')
            ->count();

        return $totalDemandes > 0
            ? round(($demandesVendues / $totalDemandes) * 100, 2)
            : 0;
    }

    /**
     * Calculer le délai moyen de vente
     */
    private function calculerDelaiMoyenVente($userId)
    {
        $demandesVendues = DemandeEpave::where('user_id', $userId)
            ->where('statut', 'vendu')
            ->get();

        if ($demandesVendues->isEmpty()) {
            return 0;
        }

        $totalJours = $demandesVendues->sum(function ($demande) {
            return $demande->created_at->diffInDays($demande->updated_at);
        });

        return round($totalJours / $demandesVendues->count(), 1);
    }
}
