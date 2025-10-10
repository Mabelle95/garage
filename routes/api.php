<?php

// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\StatsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API publiques
Route::prefix('v1')->group(function () {

    // Géolocalisation
    Route::get('/casses/nearby', [LocationController::class, 'getCassesNearby']);

    // Recherche publique
    Route::get('/search/pieces', function (Request $request) {
        $query = \App\Models\Piece::where('disponible', true)
            ->with(['vehicle.casse']);

        if ($request->filled('q')) {
            $query->where('nom', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('marque')) {
            $query->whereHas('vehicle', function($q) use($request) {
                $q->where('marque', $request->marque);
            });
        }

        return $query->paginate(20);
    });

    Route::get('/search/vehicles', function (Request $request) {
        $query = \App\Models\Vehicle::where('vendu', false)
            ->with(['casse']);

        if ($request->filled('q')) {
            $query->where(function($q) use($request) {
                $q->where('marque', 'like', '%' . $request->q . '%')
                    ->orWhere('modele', 'like', '%' . $request->q . '%');
            });
        }

        return $query->paginate(20);
    });
});

// Routes API protégées
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // Statistiques
    Route::get('/stats/dashboard', [StatsController::class, 'dashboard']);

    // Panier (AJAX)
    Route::get('/panier/count', [PanierController::class, 'count']);

    // Notifications
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);

    // Favoris
    Route::post('/favoris/{type}/{id}', function ($type, $id) {
        $modelMap = [
            'piece' => \App\Models\Piece::class,
            'vehicle' => \App\Models\Vehicle::class,
            'casse' => \App\Models\User::class,
        ];

        if (!isset($modelMap[$type])) {
            return response()->json(['error' => 'Type non valide'], 400);
        }

        $model = $modelMap[$type];
        $item = $model::findOrFail($id);

        $favori = auth()->user()->favoris()->where([
            'favori_type' => $model,
            'favori_id' => $id
        ])->first();

        if ($favori) {
            $favori->delete();
            return response()->json(['favoris' => false]);
        } else {
            auth()->user()->favoris()->create([
                'favori_type' => $model,
                'favori_id' => $id
            ]);
            return response()->json(['favoris' => true]);
        }
    });

    // Mise à jour rapide des stocks
    Route::put('/pieces/{piece}/stock', function (\App\Models\Piece $piece, Request $request) {
        if (auth()->user()->id !== $piece->vehicle->casse_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'quantite' => 'required|integer|min:0'
        ]);

        $piece->update([
            'quantite' => $request->quantite,
            'disponible' => $request->quantite > 0
        ]);

        return response()->json(['success' => true]);
    });
});

