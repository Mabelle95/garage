<?php
// app/Http/Controllers/PieceController.php

namespace App\Http\Controllers;

use App\Models\Piece;
use App\Models\Marque;
use App\Models\Modele;
use App\Models\NomPiece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PieceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isCasse()) {
            // Vue casse : toutes les pièces de l'utilisateur connecté
            $query = Piece::with(['marque', 'modele', 'nomPiece'])->where('user_id', $user->id);

            if ($request->filled('search')) {
                $query->where('nom', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('etat')) {
                $query->where('etat', $request->etat);
            }

            if ($request->filled('marque_id')) {
                $query->where('marque_id', $request->marque_id);
            }

            if ($request->filled('ville')) {
                $query->where('ville', $request->ville);
            }

            $pieces = $query->latest()->paginate(12);

            // Marques utilisées par l'utilisateur
            $marques = Marque::active()
                ->whereHas('pieces', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->withCount('pieces')
                ->orderBy('nom')
                ->get();

            // Villes des pièces de l'utilisateur
            $villes = Piece::where('user_id', $user->id)
                ->whereNotNull('ville')
                ->distinct()
                ->pluck('ville')
                ->filter()
                ->sort()
                ->values();

            return view('pieces.index', compact('pieces', 'marques', 'villes'));
        } else {
            // Vue client : marketplace
            $query = Piece::with(['marque', 'modele', 'nomPiece', 'user'])
                ->where('disponible', true);

            if ($request->filled('search')) {
                $query->where('nom', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('marque_id')) {
                $query->where('marque_id', $request->marque_id);
            }

            if ($request->filled('etat')) {
                $query->where('etat', $request->etat);
            }

            if ($request->filled('ville')) {
                $query->where('ville', $request->ville);
            }

            $pieces = $query->latest()->paginate(12);

            // Marques disponibles
            $marques = Marque::active()
                ->whereHas('pieces', function ($q) {
                    $q->where('disponible', true);
                })
                ->withCount('pieces')
                ->orderBy('nom')
                ->get();

            // Villes disponibles
            $villes = Piece::where('disponible', true)
                ->whereNotNull('ville')
                ->distinct()
                ->pluck('ville')
                ->filter()
                ->sort()
                ->values();

            return view('pieces.index', compact('pieces', 'marques', 'villes'));
        }
    }

    public function create()
    {
        $this->authorize('create', Piece::class);

        $marques = Marque::active()->orderBy('nom')->get();
        $nomPieces = NomPiece::active()->orderBy('nom')->get();

        return view('pieces.create', compact('marques', 'nomPieces'));
    }

    public function store(Request $request)
    {
        $messages = [
            'nom_piece_id.required_without' => 'Veuillez sélectionner ou ajouter un nom de pièce.',
            'new_nom_piece.required_without' => 'Veuillez sélectionner ou ajouter un nom de pièce.',
            'marque_id.required_without' => 'Veuillez sélectionner ou ajouter une marque.',
            'new_marque.required_without' => 'Veuillez sélectionner ou ajouter une marque.',
            'modele_id.required_without' => 'Veuillez sélectionner ou ajouter un modèle.',
            'new_modele.required_without' => 'Veuillez sélectionner ou ajouter un modèle.',
            'description.required' => 'La description est obligatoire.',
            'prix.required' => 'Le prix de la pièce est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre valide.',
            'prix.min' => 'Le prix doit être au moins de 500 FCFA.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.integer' => 'La quantité doit être un nombre entier.',
            'quantite.min' => 'La quantité doit être au moins de 1.',
            'etat.required' => "L'état de la pièce est obligatoire.",
            'photos.*.image' => 'Chaque fichier doit être une image valide.',
            'photos.*.max' => 'Chaque image ne doit pas dépasser 2 Mo.',
            'compatible_avec.required' => 'Le champ "compatible avec" est obligatoire.',
        ];

        $validated = $request->validate([
            'nom_piece_id' => 'required_without:new_nom_piece|nullable|exists:nom_pieces,id',
            'new_nom_piece' => 'required_without:nom_piece_id|nullable|string|max:255',
            'marque_id' => 'required_without:new_marque|nullable|exists:marques,id',
            'new_marque' => 'required_without:marque_id|nullable|string|max:255',
            'modele_id' => 'required_without:new_modele|nullable|exists:modeles,id',
            'new_modele' => 'required_without:modele_id|nullable|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:500',
            'quantite' => 'required|integer|min:1',
            'etat' => 'required|in:neuf,tres_bon,bon,moyen,usage',
            'photos.*' => 'nullable|image|max:2048',
            'compatible_avec' => 'required|string',
            'disponible' => 'nullable',
        ], $messages);

        // Gestion du nouveau nom de pièce
        if ($request->filled('new_nom_piece')) {
            $nomPiece = NomPiece::firstOrCreate(
                ['nom' => trim($request->new_nom_piece)],
                ['is_active' => true]
            );
            $validated['nom_piece_id'] = $nomPiece->id;
        }

        // Gestion de la nouvelle marque
        if ($request->filled('new_marque')) {
            $marque = Marque::firstOrCreate(
                ['nom' => trim($request->new_marque)],
                ['is_active' => true]
            );
            $validated['marque_id'] = $marque->id;
        }

        // Gestion du nouveau modèle
        if ($request->filled('new_modele')) {
            $modele = Modele::firstOrCreate(
                [
                    'marque_id' => $validated['marque_id'],
                    'nom' => trim($request->new_modele)
                ],
                ['is_active' => true]
            );
            $validated['modele_id'] = $modele->id;
        }

        // Supprimer les champs temporaires
        unset($validated['new_nom_piece'], $validated['new_marque'], $validated['new_modele']);

        // Récupérer le nom de la pièce pour le champ 'nom'
        $nomPieceObj = NomPiece::find($validated['nom_piece_id']);
        $validated['nom'] = $nomPieceObj->nom;

        // Récupérer automatiquement la ville de l'utilisateur connecté
        $validated['ville'] = Auth::user()->ville;

        // Gestion de la checkbox "disponible"
        $validated['disponible'] = true;

        // Générer la référence constructeur automatiquement
        $validated['reference_constructeur'] = $this->genererReferenceConstructeur(
            $validated['marque_id'],
            $validated['modele_id'],
            $validated['nom_piece_id']
        );

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
        $piece->load(['marque', 'modele', 'nomPiece', 'user']);

        // Vérifier que la pièce appartient à l'utilisateur si nécessaire
        if (Auth::user()->isCasse() && $piece->user_id !== Auth::id()) {
            abort(403);
        }

        $piecesSimilaires = Piece::with(['marque', 'modele', 'nomPiece'])
            ->where('nom', 'like', '%' . $piece->nom . '%')
            ->where('id', '!=', $piece->id)
            ->where('disponible', true)
            ->limit(4)
            ->get();

        return view('pieces.show', compact('piece', 'piecesSimilaires'));
    }

    public function edit(Piece $piece)
    {
        $this->authorize('update', $piece);

        $marques = Marque::active()->orderBy('nom')->get();
        $modeles = Modele::active()->where('marque_id', $piece->marque_id)->orderBy('nom')->get();
        $nomPieces = NomPiece::active()->orderBy('nom')->get();

        return view('pieces.edit', compact('piece', 'marques', 'modeles', 'nomPieces'));
    }

    public function update(Request $request, Piece $piece)
    {
        $this->authorize('update', $piece);

        $messages = [
            'nom_piece_id.required_without' => 'Veuillez sélectionner ou ajouter un nom de pièce.',
            'new_nom_piece.required_without' => 'Veuillez sélectionner ou ajouter un nom de pièce.',
            'marque_id.required_without' => 'Veuillez sélectionner ou ajouter une marque.',
            'new_marque.required_without' => 'Veuillez sélectionner ou ajouter une marque.',
            'modele_id.required_without' => 'Veuillez sélectionner ou ajouter un modèle.',
            'new_modele.required_without' => 'Veuillez sélectionner ou ajouter un modèle.',
            'description.required' => 'La description est obligatoire.',
            'prix.required' => 'Le prix de la pièce est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre valide.',
            'prix.min' => 'Le prix doit être au moins de 500 FCFA.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.integer' => 'La quantité doit être un nombre entier.',
            'quantite.min' => 'La quantité doit être au moins de 1.',
            'etat.required' => "L'état de la pièce est obligatoire.",
            'compatible_avec.required' => 'Le champ "compatible avec" est obligatoire.',
            'photos.*.image' => 'Chaque fichier doit être une image valide.',
            'photos.*.max' => 'Chaque image ne doit pas dépasser 2 Mo.',
        ];

        $validated = $request->validate([
            'nom_piece_id' => 'required_without:new_nom_piece|nullable|exists:nom_pieces,id',
            'new_nom_piece' => 'required_without:nom_piece_id|nullable|string|max:255',
            'marque_id' => 'required_without:new_marque|nullable|exists:marques,id',
            'new_marque' => 'required_without:marque_id|nullable|string|max:255',
            'modele_id' => 'required_without:new_modele|nullable|exists:modeles,id',
            'new_modele' => 'required_without:modele_id|nullable|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:500',
            'quantite' => 'required|integer|min:1',
            'etat' => 'required|in:neuf,tres_bon,bon,moyen,usage',
            'photos.*' => 'nullable|image|max:2048',
            'compatible_avec' => 'required|string',
            'disponible' => 'nullable',
        ], $messages);

        // Gestion du nouveau nom de pièce
        if ($request->filled('new_nom_piece')) {
            $nomPiece = NomPiece::firstOrCreate(
                ['nom' => trim($request->new_nom_piece)],
                ['is_active' => true]
            );
            $validated['nom_piece_id'] = $nomPiece->id;
        }

        // Gestion de la nouvelle marque
        if ($request->filled('new_marque')) {
            $marque = Marque::firstOrCreate(
                ['nom' => trim($request->new_marque)],
                ['is_active' => true]
            );
            $validated['marque_id'] = $marque->id;
        }

        // Gestion du nouveau modèle
        if ($request->filled('new_modele')) {
            $modele = Modele::firstOrCreate(
                [
                    'marque_id' => $validated['marque_id'],
                    'nom' => trim($request->new_modele)
                ],
                ['is_active' => true]
            );
            $validated['modele_id'] = $modele->id;
        }

        // Supprimer les champs temporaires
        unset($validated['new_nom_piece'], $validated['new_marque'], $validated['new_modele']);

        // Mettre à jour le nom de la pièce
        $nomPieceObj = NomPiece::find($validated['nom_piece_id']);
        $validated['nom'] = $nomPieceObj->nom;

        // Mettre à jour la ville depuis l'utilisateur actuel
        $validated['ville'] = Auth::user()->ville;

        // Régénérer la référence si changement de marque/modèle/nom
        if ($piece->marque_id != $validated['marque_id'] ||
            $piece->modele_id != $validated['modele_id'] ||
            $piece->nom_piece_id != $validated['nom_piece_id']) {
            $validated['reference_constructeur'] = $this->genererReferenceConstructeur(
                $validated['marque_id'],
                $validated['modele_id'],
                $validated['nom_piece_id']
            );
        }

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
        $validated['disponible'] = true;

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

    // API pour récupérer les modèles d'une marque
    public function getModeles($marqueId)
    {
        $modeles = Modele::active()
            ->where('marque_id', $marqueId)
            ->orderBy('nom')
            ->get(['id', 'nom']);

        return response()->json($modeles);
    }

    // API pour l'autocomplétion des noms de pièces
    public function autocompleteNomPieces(Request $request)
    {
        $search = $request->get('q', '');

        $nomPieces = NomPiece::active()
            ->where('nom', 'like', '%' . $search . '%')
            ->orderBy('nom')
            ->limit(10)
            ->get(['id', 'nom', 'categorie']);

        return response()->json($nomPieces);
    }

    // API pour l'autocomplétion des marques
    public function autocompleteMarques(Request $request)
    {
        $search = $request->get('q', '');

        $marques = Marque::active()
            ->where('nom', 'like', '%' . $search . '%')
            ->orderBy('nom')
            ->limit(10)
            ->get(['id', 'nom']);

        return response()->json($marques);
    }

    // Générer une référence constructeur unique
    private function genererReferenceConstructeur($marqueId, $modeleId, $nomPieceId)
    {
        $marque = Marque::find($marqueId);
        $modele = Modele::find($modeleId);
        $nomPiece = NomPiece::find($nomPieceId);

        // Format: MARQUE-MODELE-PIECE-NUMERO
        // Ex: TOYOTA-COROLLA-MOTEUR-0001
        $marqueCode = strtoupper(substr($this->removeAccents($marque->nom), 0, 6));
        $modeleCode = strtoupper(substr($this->removeAccents($modele->nom), 0, 6));
        $pieceCode = strtoupper(substr($this->removeAccents($nomPiece->nom), 0, 6));

        // Compter les pièces similaires pour générer le numéro
        $count = Piece::where('marque_id', $marqueId)
            ->where('modele_id', $modeleId)
            ->where('nom_piece_id', $nomPieceId)
            ->count();

        $numero = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return "{$marqueCode}-{$modeleCode}-{$pieceCode}-{$numero}";
    }

    // Retirer les accents pour la référence
    private function removeAccents($string)
    {
        $unwanted = [
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'Ç' => 'C', 'ç' => 'c', 'Ñ' => 'N', 'ñ' => 'n'
        ];
        return strtr($string, $unwanted);
    }
}
