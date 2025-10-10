<?php

namespace App\Http\Controllers;

use App\Models\Piece;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');
        $ville = $request->get('ville'); // <--- récupération du filtre ville

        $results = [];

        if ($query || $ville) {
            if ($type === 'all' || $type === 'pieces') {
                $piecesQuery = Piece::where('disponible', true)
                    ->where('nom', 'like', "%{$query}%")
                    ->with(['vehicle.casse']);

                // Filtrer par ville si précisé
                if ($ville) {
                    $piecesQuery->where('ville', $ville);
                }

                $results['pieces'] = $piecesQuery->take(10)->get();
            }

            if ($type === 'all' || $type === 'vehicles') {
                $vehiclesQuery = Vehicle::where('vendu', false)
                    ->where(function($q) use ($query) {
                        $q->where('marque', 'like', "%{$query}%")
                          ->orWhere('modele', 'like', "%{$query}%");
                    })
                    ->with('casse');

                // Filtrer par ville si précisé
                if ($ville) {
                    $vehiclesQuery->whereHas('casse', function($q) use ($ville) {
                        $q->where('ville', $ville);
                    });
                }

                $results['vehicles'] = $vehiclesQuery->take(10)->get();
            }

            if ($type === 'all' || $type === 'casses') {
                $cassesQuery = User::where('role', 'casse')
                    ->where('actif', true)
                    ->where(function($q) use ($query) {
                        $q->where('nom_entreprise', 'like', "%{$query}%")
                          ->orWhere('ville', 'like', "%{$query}%");
                    });

                // Filtrer par ville si précisé
                if ($ville) {
                    $cassesQuery->where('ville', $ville);
                }

                $results['casses'] = $cassesQuery->take(10)->get();
            }
        }

        return view('search.results', compact('results', 'query', 'type', 'ville'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'pieces');
        $ville = $request->get('ville');

        $results = [];

        if ($query) {
            if ($type === 'pieces') {
                $piecesQuery = Piece::where('disponible', true)
                    ->where('nom', 'like', "%{$query}%")
                    ->select('nom')
                    ->distinct();

                if ($ville) {
                    $piecesQuery->where('ville', $ville);
                }

                $results = $piecesQuery->take(10)->pluck('nom');
            } elseif ($type === 'vehicles') {
                $marquesQuery = Vehicle::where('marque', 'like', "%{$query}%")
                    ->select('marque')
                    ->distinct();

                $modelesQuery = Vehicle::where('modele', 'like', "%{$query}%")
                    ->select('modele')
                    ->distinct();

                if ($ville) {
                    $marquesQuery->whereHas('casse', fn($q) => $q->where('ville', $ville));
                    $modelesQuery->whereHas('casse', fn($q) => $q->where('ville', $ville));
                }

                $results = $marquesQuery->pluck('marque')
                    ->concat($modelesQuery->pluck('modele'))
                    ->unique()
                    ->take(10);
            }
        }

        return response()->json($results);
    }
}
