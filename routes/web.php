<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    Admin\AdminMessageController,
    DashboardController,
    PieceController,
    PanierController,
    CommandeController,
    DemandeEpaveController,
    SearchController,
    NotificationController,
    ProfileController,
    VehicleController,
    VenteEpaveController,
    PaymentController,
    MessageController};
use App\Http\Controllers\auth\AdminController;
use App\Models\Commande;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil publique
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
Route::middleware(['auth', 'verified', 'approved'])->group(function () {

    // NOUVELLE ROUTE : Refuser une offre
    Route::post('/demandes-epaves/{demandeEpave}/refuser-offre/{offre}', [DemandeEpaveController::class, 'refuserOffre'])
        ->name('demandes-epaves.refuser-offre');

    // Dashboard général
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
        Route::get('/{notification}/view', [NotificationController::class, 'view'])->name('view'); // NOUVELLE ROUTE
        Route::put('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    // ----------------------
    // ROUTES MESSAGERIE (NOUVEAU)
    // ----------------------
// ----------------------
// ROUTES MESSAGERIE (STYLE CHAT)
// ----------------------
    Route::prefix('messages')->name('messages.')->group(function () {
        // Interface principale du chat
        Route::get('/', [MessageController::class, 'index'])->name('index');

        // Récupérer une conversation avec un utilisateur
// Route pour la vue HTML
        Route::get('/conversation/{userId}', [MessageController::class, 'showConversation'])->name('conversation');

// Route API pour AJAX
        Route::get('/api/conversation/{userId}', [MessageController::class, 'getConversation'])->name('conversation.api');
        // Envoyer un message rapide (AJAX)
        Route::post('/send-quick', [MessageController::class, 'sendQuick'])->name('send-quick');

        // Marquer une conversation comme lue
        Route::post('/mark-conversation-read/{userId}', [MessageController::class, 'markConversationAsRead'])->name('mark-conversation-read');

        // Formulaire nouveau message (modal)
        Route::get('/create', [MessageController::class, 'create'])->name('create');
        Route::post('/', [MessageController::class, 'store'])->name('store');

        // Message rapide depuis commande (modal dans l'index des commandes)
        Route::post('/envoyer-rapide', [MessageController::class, 'envoyerRapide'])->name('envoyer-rapide');

        // Marquer tous les messages comme lus
        Route::put('/marquer-tous-lus', [MessageController::class, 'marquerTousCommeLus'])->name('marquer-tous-lus');

        // API - Nombre de messages non lus
        Route::get('/api/nombre-non-lus', [MessageController::class, 'getNombreNonLus'])->name('api.nombre-non-lus');

        // Vue détaillée d'un message (ancienne version - pour compatibilité)
        Route::get('/{message}', [MessageController::class, 'show'])->name('show');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
        Route::put('/{message}/marquer-lu', [MessageController::class, 'marquerCommeLu'])->name('marquer-lu');
    });
    // Pièces détachées
    Route::resource('pieces', PieceController::class);

    // Demandes d'épaves
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
    });

    // Commandes client
    Route::prefix('commandes')->name('commandes.')->group(function () {
        Route::get('/', [CommandeController::class, 'index'])->name('index');
        Route::get('/create', [CommandeController::class, 'create'])->name('create');
        Route::post('/', [CommandeController::class, 'store'])->name('store');

        Route::post('/confirme', [CommandeController::class, 'confirme'])->name('confirme');

        Route::get('/{commande}', [CommandeController::class, 'show'])->name('show');
        Route::delete('/{commande}/annuler', [CommandeController::class, 'annuler'])->name('annuler');
        Route::put('/{commande}/update-adresse', [CommandeController::class, 'updateAdresse'])->name('update-adresse');
    });
    // });

    // ----------------------
    // Routes Casse
    // ----------------------
    Route::middleware(['role:casse'])->prefix('gestion')->name('gestion.')->group(function () {

        // CRUD Véhicules
        Route::resource('vehicles', VehicleController::class);

        // Commandes de la casse
        Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
        Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
        Route::get('/commandes/{commande}/statut/form', [CommandeController::class, 'editStatut'])->name('commandes.edit-statut');
        Route::put('/commandes/{commande}/statut', [CommandeController::class, 'updateStatut'])->name('commandes.update-statut');

        // Stocks
        Route::get('/stocks', function () {
            $user = Auth::user();

            // Toutes les pièces de la casse (utilisateur connecté)
            $pieces = $user->pieces()->get();

            // Statistiques
            $totalPieces = $pieces->count();
            $totalStock = $pieces->sum('quantite');
            $piecesDisponibles = $pieces->where('disponible', true)->count();

            // Alertes de stock
            $stockFaible = $pieces->where('quantite', '>', 0)->where('quantite', '<=', 3);
            $stockVide = $pieces->where('quantite', 0);

            return view('gestion.stocks', compact(
                'pieces',
                'totalPieces',
                'totalStock',
                'piecesDisponibles',
                'stockFaible',
                'stockVide'
            ));
        })->name('stocks');

        // Epaves
        Route::get('/epaves', [VenteEpaveController::class, 'index'])->name('epaves.index');
        Route::get('/epaves/{demande}', [VenteEpaveController::class, 'show'])->name('epaves.show');
    });

    // ----------------------
    // Routes Admin
    // ----------------------
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {


        Route::prefix('messages')->name('messages.')->group(function () {
            // Interface principale de consultation des messages
            Route::get('/', [AdminMessageController::class, 'index'])->name('index');

            // Récupérer une conversation entre deux utilisateurs
            Route::get('/conversation/{user1Id}/{user2Id}', [AdminMessageController::class, 'getConversation'])->name('conversation');

            // Statistiques des messages
            Route::get('/statistics', [AdminMessageController::class, 'statistics'])->name('statistics');

            // Recherche dans les messages
            Route::get('/search', [AdminMessageController::class, 'search'])->name('search');
        });


        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Gestion des utilisateurs
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');

        // Statistiques
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');

        // Paramètres
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

        // Gestion des casses en attente
        Route::get('/casses/pending', [AdminController::class, 'pendingCasses'])->name('casses.pending');
        Route::post('/casses/{id}/approve', [AdminController::class, 'approveCasse'])->name('casses.approve');
        Route::post('/casses/{id}/reject', [AdminController::class, 'rejectCasse'])->name('casses.reject');
    });
});

// ----------------------
// API Routes pour autocomplétion
// ----------------------
Route::middleware('auth')->group(function() {
    // Modèles par marque
    Route::get('/api/marques/{marque}/modeles', [PieceController::class, 'getModeles']);

    // Autocomplétion noms de pièces
    Route::get('/api/pieces/autocomplete-noms', [PieceController::class, 'autocompleteNomPieces']);

    // Autocomplétion marques
    Route::get('/api/marques/autocomplete', [PieceController::class, 'autocompleteMarques']);
});

// À ajouter dans la section API Routes de web.php

Route::middleware('auth')->group(function() {
    // Modèles par marque (existant)
    Route::get('/api/marques/{marque}/modeles', [PieceController::class, 'getModeles']);

    // NOUVEAU: Modèles par marque pour les demandes d'épaves
    Route::get('/api/marques/{marque}/modeles-epave', [DemandeEpaveController::class, 'getModelesByMarque']);

    // Autocomplétion noms de pièces (existant)
    Route::get('/api/pieces/autocomplete-noms', [PieceController::class, 'autocompleteNomPieces']);

    // Autocomplétion marques (existant)
    Route::get('/api/marques/autocomplete', [PieceController::class, 'autocompleteMarques']);
});



// ----------------------
// Route publique Commandes Casse (éviter conflit noms)
// ----------------------
// Route::get('/commandes-casse', function () {
//     $commandes = Commande::whereHas('items.piece.vehicle', function ($query) {
//         $query->where('casse_id', auth()->id());
//     })->with(['client', 'items.piece'])->latest()->paginate(10);

//     return view('casse.commandes.index', compact('commandes'));
// })->name('gestion.commandes.index');

Route::get('/auth/pending-approval', function() {
    return view('auth.pending-approval');
})->name('auth.pending-approval');

// Route::get('/pay', function () {
//     return view('pay.index');
// });
// Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
// Routes de paiement
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/', [PaymentController::class, 'show'])->name('show');
    Route::post('/initiate', [PaymentController::class, 'initiate'])->name('initiate');
    Route::get('/callback', [PaymentController::class, 'callback'])->name('callback');
    Route::get('/success/{transactionId}', [PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
});

// Webhook (exclu du CSRF)
Route::post('/fedapay/webhook', [PaymentController::class, 'webhook'])->name('fedapay.webhook');

// Route de test
Route::get('/test-fedapay', function() {
    try {
        \FedaPay\FedaPay::setApiKey(config('fedapay.secret_key'));
        \FedaPay\FedaPay::setEnvironment(config('fedapay.environment'));

        return response()->json([
            'status' => 'OK',
            'environment' => config('fedapay.environment'),
            'has_public_key' => !empty(config('fedapay.public_key')),
            'has_secret_key' => !empty(config('fedapay.secret_key')),
            'callback_url' => config('fedapay.callback_url'),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage()
        ], 500);
    }
});
