<?php
// app/Http/Middleware/ShareCasseData.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\VenteEpave;
use App\Models\Notification;

class ShareCasseData
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isCasse()) {
            $user = auth()->user();

            $commandesEnAttente = Commande::where('casse_id', $user->id)
                ->where('statut', 'en_attente')
                ->count();

            $demandesEpaves = VenteEpave::where(function($query) {
                $query->where('statut', 'en_attente')
                    ->orWhere('statut', 'en_cours');
            })
                ->count();

            $notificationsNonLues = Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();

            // Partager les données avec toutes les vues
            view()->share([
                'commandesEnAttente' => $commandesEnAttente,
                'demandesEpaves' => $demandesEpaves,
                'notificationsNonLues' => $notificationsNonLues
            ]);
        } else {
            // Valeurs par défaut si l'utilisateur n'est pas une casse
            view()->share([
                'commandesEnAttente' => 0,
                'demandesEpaves' => 0,
                'notificationsNonLues' => 0
            ]);
        }

        return $next($request);
    }
}
