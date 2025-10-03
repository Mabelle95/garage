<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    PieceController,
    PanierController,
    CommandeController,
    DemandeEpaveController,
    SearchController,
    NotificationController,
    ProfileController,
    VehicleController,
    VenteEpaveController
};
use App\Models\User;
use App\Models\Commande;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d’accueil publique
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Recherche
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Authentification
require __DIR__ . '/auth.php';

// ----------------------
// Routes protégées
// ----------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // Tableau de bord général
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::put('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    // Pièces détachées
    Route::resource('pieces', PieceController::class);

    // Demandes d’épaves
    Route::resource('demandes-epaves', DemandeEpaveController::class)
        ->parameters(['demandes-epaves' => 'demandeEpave'])
        ->names('demandes-epaves');

    Route::post('/demandes-epaves/{demandeEpave}/offre', [DemandeEpaveController::class, 'faireOffre'])
        ->name('demandes-epaves.faire-offre');
    Route::delete('/demandes-epaves/{demandeEpave}/offre/{offre}', [DemandeEpaveController::class, 'retirerOffre'])
        ->name('demandes-epaves.retirer-offre');
    Route::post('/demandes-epaves/{demandeEpave}/accepter-offre/{offre}', [DemandeEpaveController::class, 'accepterOffre'])
        ->name('demandes-epaves.accepter-offre');

    // ----------------------
    // Routes Client
    // ----------------------
    Route::middleware(['role:client'])->group(function () {

        // Panier
        Route::prefix('panier')->name('panier.')->group(function () {
            Route::get('/', [PanierController::class, 'index'])->name('index');
            Route::post('/add/{piece}', [PanierController::class, 'add'])->name('add');
            Route::put('/items/{item}', [PanierController::class, 'update'])->name('update');
            Route::delete('/items/{item}', [PanierController::class, 'remove'])->name('remove');
            Route::delete('/clear', [PanierController::class, 'clear'])->name('clear');
        });

        // Commandes client
        Route::prefix('commandes')->name('commandes.')->group(function () {
            Route::get('/', [CommandeController::class, 'index'])->name('index');
            Route::get('/create', [CommandeController::class, 'create'])->name('create');
            Route::post('/', [CommandeController::class, 'store'])->name('store');
            Route::get('/{commande}', [CommandeController::class, 'show'])->name('show');
            Route::delete('/{commande}/annuler', [CommandeController::class, 'annuler'])->name('annuler');
            Route::put('/{commande}/update-adresse', [CommandeController::class, 'updateAdresse'])->name('update-adresse');
        });
    });

    // ----------------------
    // Routes Casse
    // ----------------------
    Route::middleware(['role:casse'])->prefix('gestion')->name('gestion.')->group(function () {

        // CRUD Véhicules
        Route::resource('vehicles', VehicleController::class);

        // Commandes de la casse
        Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');


        // Détail commande
        Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');

        //Redirection vers le formulaire de mise à jour
        Route::get('/commandes/{commande}/statut/form', [CommandeController::class, 'editStatut'])->name('commandes.edit-statut');

        // Mise à jour statut
        Route::put('/commandes/{commande}/statut', [CommandeController::class, 'updateStatut'])->name('commandes.update-statut');

        // Stocks
        Route::get('/stocks', function () {
               $vehicles = Auth::user()->vehicles()->with('pieces')->get();
        $totalPieces = Auth::user()->pieces()->count();
        $totalStock = Auth::user()->pieces()->sum('quantite');
        $piecesDisponibles = Auth::user()->pieces()->where('disponible', true)->count();

            return view('gestion.stocks', compact('vehicles', 'totalPieces', 'totalStock', 'piecesDisponibles'));
        })->name('stocks');
        // Route::resource('epaves', VenteEpaveController::class)->names([
        //     'index' => 'epaves.index',
        //     'create' => 'epaves.create',
        //     'store' => 'epaves.store',
        //     'show' => 'epaves.show',
        //     'edit' => 'epaves.edit',
        //     'update' => 'epaves.update',
        //     'destroy' => 'epaves.destroy',
        // ]);

        Route::get('/epaves', [VenteEpaveController::class, 'index'])->name('epaves.index');
        Route::get('/epaves/{demande}', [VenteEpaveController::class, 'show'])->name('epaves.show');
    });
    

    // ----------------------
    // Routes Admin
    // ----------------------
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', function () {
            $casses = User::with('vehicles', 'pieces')->where('role', 'casse')->get();
            $clients = User::with('commandes')->where('role', 'client')->get();


        $commandes = DB::table('commandes')
        ->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

    // Transformer les données pour Chart.js
    $labels = [];
    $data = [];

    foreach ($commandes as $commande) {
        $labels[] = date("F", mktime(0, 0, 0, $commande->mois, 1)); // Nom du mois
        $data[] = $commande->total;
    }

            return view('admin.dashboard', compact('casses', 'clients', 'data',));
        })->name('dashboard');

        // Gestion utilisateurs
        Route::get('/users', function () {
            $casses = User::with(['vehicles', 'pieces'])->where('role', 'casse')->get();
            $clients = User::with(['commandes'])->where('role', 'client')->get();

            return view('admin.users.index', compact('casses', 'clients'));
        })->name('users.index');

        // Statistiques
        Route::get('/statistics', function () {
            return view('admin.statistics.index');
        })->name('statistics');

        // Paramètres
        Route::get('/settings', function () {
            return view('admin.settings.index');
        })->name('settings');
    });
});
 Route::get('/commandes', function () {
            $commandes = Commande::whereHas('items.piece.vehicle', function ($query) {
                $query->where('casse_id', auth()->id());
            })->with(['client', 'items.piece'])->latest()->paginate(10);

            return view('casse.commandes.index', compact('commandes'));
        })->name('commandes');
