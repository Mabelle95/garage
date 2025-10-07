<?php

// app/Http/Controllers/PanierController.php
namespace App\Http\Controllers;

use App\Models\Piece;
use App\Models\PanierItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    public function index()
    {
        $panier = Auth::user()->panier()->with(['items.piece.vehicle.casse'])->first();

        if (!$panier) {
            $panier = Auth::user()->panier()->create();
        }

        return view('panier.index', compact('panier'));
    }

    public function add(Request $request, Piece $piece)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1|max:' . $piece->quantite
        ]);

        if (!$piece->disponible) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette pièce n\'est plus disponible.'
                ]);
            }
            return back()->with('error', 'Cette pièce n\'est plus disponible.');
        }

        $panier = Auth::user()->panier;
        if (!$panier) {
            $panier = Auth::user()->panier()->create();
        }

        $existingItem = $panier->items()->where('piece_id', $piece->id)->first();

        if ($existingItem) {
            $newQuantite = $existingItem->quantite + $request->quantite;
            if ($newQuantite > $piece->quantite) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Quantité demandée supérieure au stock disponible.'
                    ]);
                }
                return back()->with('error', 'Quantité demandée supérieure au stock disponible.');
            }
            $existingItem->update(['quantite' => $newQuantite]);
        } else {
            $panier->items()->create([
                'piece_id' => $piece->id,
                'quantite' => $request->quantite
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pièce ajoutée au panier avec succès.'
            ]);
        }

        return back()->with('success', 'Pièce ajoutée au panier avec succès.');
    }

    public function update(Request $request, PanierItem $item)
    {
        $this->authorize('update', $item);

        $request->validate([
            'quantite' => 'required|integer|min:1|max:' . $item->piece->quantite
        ]);

        $item->update(['quantite' => $request->quantite]);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(PanierItem $item)
    {
        $this->authorize('delete', $item);
        $item->delete();

        return back()->with('success', 'Pièce retirée du panier.');
    }

    public function clear()
    {
        Auth::user()->panier->items()->delete();
        return back()->with('success', 'Panier vidé.');
    }

    public function count()
    {
        $count = Auth::user()->panier->items()->sum('quantite');
        return response()->json(['count' => $count]);
    }
}
