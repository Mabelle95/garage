<?php

// app/Http/Controllers/VehicleController.php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Piece;
use Cassandra\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isCasse()) {
            $query = $user->vehicles()->with('pieces');

            if ($request->filled('search')) {
                $query->where(function($q) use($request) {
                    $q->where('marque', 'like', '%' . $request->search . '%')
                        ->orWhere('modele', 'like', '%' . $request->search . '%')
                        ->orWhere('numero_plaque', 'like', '%' . $request->search . '%');
                });
            }

            $vehicles = $query->latest()->paginate(10);
            return view('vehicles.index', compact('vehicles'));
        } else {
            // Marketplace pour clients
            $query = Vehicle::where('vendu', false)->with(['casse', 'pieces']);

            if ($request->filled('search')) {
                $query->where(function($q) use($request) {
                    $q->where('marque', 'like', '%' . $request->search . '%')
                        ->orWhere('modele', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->filled('marque')) {
                $query->where('marque', $request->marque);
            }

            if ($request->filled('prix_max')) {
                $query->where('prix_epave', '<=', $request->prix_max);
            }

            // Géolocalisation
            if ($request->filled('latitude') && $request->filled('longitude')) {
                $lat = $request->latitude;
                $lng = $request->longitude;
                $radius = $request->radius ?? 50; // km

                $query->whereHas('casse', function($q) use($lat, $lng, $radius) {
                    $q->whereRaw("
                        (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                        sin(radians(latitude)))) <= ?
                    ", [$lat, $lng, $lat, $radius]);
                });
            }

            $vehicles = $query->latest()->paginate(12);
            $marques = Vehicle::distinct()->pluck('marque');

            return view('vehicles.marketplace', compact('vehicles', 'marques'));
        }
    }

    public function create()
    {
        $this->authorize('create', Vehicle::class);
        return view('vehicles.create');
    }


    function generateUniqueNumeroChassis(): string
    {
        // Récupérer le dernier enregistrement
        $lastVehicle = Vehicle::orderBy('id', 'desc')->first();

        if (!$lastVehicle) {
            // Si aucun enregistrement, on commence par le premier
            return 'CH-00001';
        }

        // Extraire la partie numérique (après CH-)
        $lastNumber = (int) str_replace('CH-', '', $lastVehicle->numero_chassis);

        // Incrémenter
        $newNumber = $lastNumber + 1;

        // Générer le nouveau code formaté avec 5 chiffres
        return 'CH-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {

        $this->authorize('create', Vehicle::class);

        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'numero_chassis' => 'nullable|string|unique:vehicles',
            'numero_plaque' => 'required|string|unique:vehicles',
            'couleur' => 'required|string',
            'carburant' => 'required|in:essence,diesel,hybride,electrique',
            'transmission' => 'required|in:manuelle,automatique',
            'kilometrage' => 'required|integer|min:0',
            'etat' => 'required|in:bon,moyen,mauvais,epave',
            'date_arrivee' => 'nullable|date',
            'prix_epave' => 'nullable|numeric|min:0',
            'photo_principale' => 'nullable|image|max:2048',
            'photos_additionnelles.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $validated['casse_id'] = Auth::id();
        $validated['numero_chassis'] = $this->generateUniqueNumeroChassis();
        $validated['date_arrivee'] = now()->toDateString(); // ex: 2025-09-27

        // Upload photo principale
        if ($request->hasFile('photo_principale')) {
            $validated['photo_principale'] = $request->file('photo_principale')
                ->store('vehicles', 'public');
        }

        // Upload photos additionnelles
        if ($request->hasFile('photos_additionnelles')) {
            $photos = [];
            foreach ($request->file('photos_additionnelles') as $photo) {
                $photos[] = $photo->store('vehicles', 'public');
            }
            $validated['photos_additionnelles'] = $photos;
        }

        $vehicle = Vehicle::create($validated);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Véhicule ajouté avec succès.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['casse', 'pieces' => function($query) {
            $query->where('disponible', true);
        }]);

        $vehiclesSimilaires = Vehicle::where('marque', $vehicle->marque)
            ->where('modele', $vehicle->modele)
            ->where('id', '!=', $vehicle->id)
            ->where('vendu', false)
            ->take(4)->get();

        return view('vehicles.show', compact('vehicle', 'vehiclesSimilaires'));
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'couleur' => 'required|string',
            'carburant' => 'required|in:essence,diesel,hybride,electrique',
            'transmission' => 'required|in:manuelle,automatique',
            'kilometrage' => 'required|integer|min:0',
            'etat' => 'required|in:bon,moyen,mauvais,epave',
            'prix_epave' => 'required|numeric|min:0',
            'photo_principale' => 'nullable|image|max:2048',
            'photos_additionnelles.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'vendu' => 'boolean'
        ]);

        // Gestion des photos
        if ($request->hasFile('photo_principale')) {
            if ($vehicle->photo_principale) {
                Storage::disk('public')->delete($vehicle->photo_principale);
            }
            $validated['photo_principale'] = $request->file('photo_principale')
                ->store('vehicles', 'public');
        }

        if ($request->hasFile('photos_additionnelles')) {
            if ($vehicle->photos_additionnelles) {
                foreach ($vehicle->photos_additionnelles as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }
            $photos = [];
            foreach ($request->file('photos_additionnelles') as $photo) {
                $photos[] = $photo->store('vehicles', 'public');
            }
            $validated['photos_additionnelles'] = $photos;
        }

        $vehicle->update($validated);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Véhicule mis à jour avec succès.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        // Supprimer les photos
        if ($vehicle->photo_principale) {
            Storage::disk('public')->delete($vehicle->photo_principale);
        }
        if ($vehicle->photos_additionnelles) {
            foreach ($vehicle->photos_additionnelles as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Véhicule supprimé avec succès.');
    }
}
