<?php

namespace App\Providers;

use App\Models\Commande;
use App\Models\Notification;
use App\Models\Piece;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */


    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */



        public function boot()
    {

        Schema::defaultStringLength(191);


        // Partager le nombre de notifications non lues avec toutes les vues
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $notificationsNonLues = Auth::user()->notifications()
                    ->where('lu', false)
                    ->count();

                $view->with('notificationsNonLues', $notificationsNonLues);
            }
        });




        View::composer('layouts.casse', function ($view) {
            $casseId = auth()->check() ? auth()->user()->casse_id : null;

            if (!$casseId) {
                return;
            }

            $stats = [
                'vehicules' => Vehicule::where('casse_id', $casseId)->count(),
                'pieces' => Piece::where('casse_id', $casseId)->count(),
                'commandes' => Commande::where('casse_id', $casseId)->count(),
                'revenus' => Commande::where('casse_id', $casseId)
                    ->where('statut', 'livree')
                    ->sum('total')
            ];

            $commandesRecentes = Commande::with('client')
                ->where('casse_id', $casseId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $commandesEnAttente = Commande::where('casse_id', $casseId)
                ->where('statut', 'en_attente')
                ->count();

            $demandesEpaves = Commande::where('casse_id', $casseId)
                ->where('statut', 'en_attente')
                ->count();

            $notificationsNonLues = Notification::where('user_id', auth()->id())
                ->whereNull('read_at')
                ->count();

            $view->with(compact(
                'stats',
                'commandesRecentes',
                'commandesEnAttente',
                'demandesEpaves',
                'notificationsNonLues'
            ));
        });
    }
}
