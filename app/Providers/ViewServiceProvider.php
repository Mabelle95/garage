<?php
// app/Providers/ViewServiceProvider.php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Commande;
use App\Models\VenteEpave;
use App\Models\Notification;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.casse', function ($view) {
            $user = auth()->user();

            if ($user && $user->isCasse()) {
                $commandesEnAttente = Commande::where('casse_id', $user->id)
                    ->where('statut', 'en_attente')
                    ->count();

                $demandesEpaves = VenteEpave::where('statut', 'en_attente')
                    ->orWhere('statut', 'en_cours')
                    ->count();

                $notificationsNonLues = Notification::where('user_id', $user->id)
                    ->whereNull('read_at')
                    ->count();

                $view->with([
                    'commandesEnAttente' => $commandesEnAttente,
                    'demandesEpaves' => $demandesEpaves,
                    'notificationsNonLues' => $notificationsNonLues
                ]);
            }
        });
    }
}
