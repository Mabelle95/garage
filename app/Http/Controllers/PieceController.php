<?php

namespace App\Http\Controllers;

use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PieceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isCasse()) {
            // Vue casse : toutes les pièces de l'utilisateur connecté
            $query = Piece::where('user_id', $user->id);

            if ($request->filled('search')) {
                $query->where('nom', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('etat')) {
                $query->where('etat', $request->etat);
            }

            if ($request->filled('ville')) {
                $query->where('ville', $request->ville);
            }

            $pieces = $query->latest()->paginate(12);

            // Marques et villes pour filtres dynamiques
            $marques = $pieces->pluck('marque_piece')->unique()->filter();
            $villes  = $pieces->pluck('ville')->unique()->filter();

            return view('pieces.index', compact('pieces', 'marques', 'villes'));
        } else {
            // Vue client : marketplace
            $query = Piece::where('disponible', true);

            if ($request->filled('search')) {
                $query->where('nom', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('marque')) {
                $query->where('marque_piece', $request->marque);
            }

            if ($request->filled('etat')) {
                $query->where('etat', $request->etat);
            }

            if ($request->filled('ville')) {
                $query->where('ville', $request->ville);
            }

            $pieces = $query->latest()->paginate(12);

            $marques = $pieces->pluck('marque_piece')->unique()->filter();
            $villes  = $pieces->pluck('ville')->unique()->filter();

            return view('pieces.index', compact('pieces', 'marques', 'villes'));
        }
    }

    public function create()
    {
        $this->authorize('create', Piece::class);

        return view('pieces.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'nom.required' => 'Le nom de la pièce est obligatoire.',
            'marque_piece.required' => 'La marque de la pièce est obligatoire.',
            'modele_piece.required' => 'Le modèle de la pièce est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'prix.required' => 'Le prix de la pièce est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre valide.',
            'prix.min' => 'Le prix doit être au moins de 500 FCFA.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.integer' => 'La quantité doit être un nombre entier.',
            'quantite.min' => 'La quantité doit être au moins de 1.',
            'etat.required' => "L'état de la pièce est obligatoire.",
            'etat.max' => "L'état ne doit pas dépasser 255 caractères.",
            'ville.required' => 'La ville est obligatoire.',
            'ville.max' => 'La ville ne doit pas dépasser 255 caractères.',
            'photos.*.image' => 'Chaque fichier doit être une image valide.',
            'photos.*.max' => 'Chaque image ne doit pas dépasser 2 Mo.',
            'reference_constructeur.required' => 'La référence constructeur est obligatoire.',
            'reference_constructeur.max' => 'La référence constructeur ne doit pas dépasser 255 caractères.',
            'compatible_avec.required' => 'Le champ "compatible avec" est obligatoire.',
            'compatible_avec.string' => 'Le champ "compatible avec" doit être du texte.',
        ];

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'marque_piece' => 'required|string|max:255',
            'modele_piece' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:500',
            'quantite' => 'required|integer|min:1',
            'etat' => 'required|string|max:255',
            'photos.*' => 'nullable|image|max:2048',
            'reference_constructeur' => 'required|string|max:255',
            'compatible_avec' => 'required|string',
            'disponible' => 'nullable', // <-- plus de 'boolean'
            'ville' => 'required|string|max:255',
        ], $messages);


        // Gestion de la checkbox "disponible"
        $validated['disponible'] = $request->has('disponible');

        // Gestion des photos
        if ($request->hasFile('photos')) {
            $validated['photos'] = array_map(
                fn($file) => $file->store('pieces', 'public'),
                $request->file('photos')
            );
        }

        // Attribution de l'utilisateur connecté
        $validated['user_id'] = Auth::id();

        // Création de la pièce
        Piece::create($validated);

        return redirect()->route('pieces.index')
            ->with('success', 'La pièce a été ajoutée avec succès.');
    }

    public function show(Piece $piece)
    {
        // Vérifier que la pièce appartient à l'utilisateur si nécessaire
        if (Auth::user()->isCasse() && $piece->user_id !== Auth::id()) {
            abort(403);
        }

        $piecesSimilaires = Piece::where('nom', 'like', '%' . $piece->nom . '%')
            ->where('id', '!=', $piece->id)
            ->where('disponible', true)
            ->limit(4)
            ->get();

        return view('pieces.show', compact('piece', 'piecesSimilaires'));
    }

    public function edit(Piece $piece)
    {
        $this->authorize('update', $piece);

        return view('pieces.edit', compact('piece'));
    }

    public function update(Request $request, Piece $piece)
    {
        $this->authorize('update', $piece);

        $messages = [
            'nom.required' => 'Le nom de la pièce est obligatoire.',
            'marque_piece.required' => 'La marque de la pièce est obligatoire.',
            'modele_piece.required' => 'Le modèle de la pièce est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'prix.required' => 'Le prix de la pièce est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre valide.',
            'prix.min' => 'Le prix doit être au moins de 500 FCFA.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.integer' => 'La quantité doit être un nombre entier.',
            'quantite.min' => 'La quantité doit être au moins de 1.',
            'etat.required' => "L'état de la pièce est obligatoire.",
            'etat.max' => "L'état ne doit pas dépasser 255 caractères.",
            'ville.required' => 'La ville est obligatoire.',
            'ville.max' => 'La ville ne doit pas dépasser 255 caractères.',
            'reference_constructeur.required' => 'La référence constructeur est obligatoire.',
            'reference_constructeur.max' => 'La référence constructeur ne doit pas dépasser 255 caractères.',
            'compatible_avec.required' => 'Le champ "compatible avec" est obligatoire.',
            'compatible_avec.string' => 'Le champ "compatible avec" doit être du texte.',
            'photos.*.image' => 'Chaque fichier doit être une image valide.',
            'photos.*.max' => 'Chaque image ne doit pas dépasser 2 Mo.',
        ];

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'marque_piece' => 'required|string|max:255',
            'modele_piece' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:500',
            'quantite' => 'required|integer|min:1',
            'etat' => 'required|string|max:255',
            'photos.*' => 'nullable|image|max:2048',
            'reference_constructeur' => 'required|string|max:255',
            'compatible_avec' => 'required|string',
            'disponible' => 'nullable',
            'ville' => 'required|string|max:255',
        ], $messages);

        // Gestion des photos
        if ($request->hasFile('photos')) {
            if ($piece->photos) {
                foreach ($piece->photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }
            $validated['photos'] = array_map(
                fn($file) => $file->store('pieces', 'public'),
                $request->file('photos')
            );
        }

        // Checkbox disponible
        $validated['disponible'] = $request->has('disponible');

        $piece->update($validated);

        return redirect()->route('pieces.show', $piece)
            ->with('success', 'La pièce a été mise à jour avec succès.');
    }

    public function destroy(Piece $piece)
    {
        $this->authorize('delete', $piece);

        if ($piece->photos) {
            foreach ($piece->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $piece->delete();

        return redirect()->route('pieces.index')
            ->with('success', 'Pièce supprimée avec succès.');
    }
}
