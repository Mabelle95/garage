<?php



// app/Http/Controllers/FavorisController.php
namespace App\Http\Controllers;

use App\Models\Favoris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    public function index()
    {
        $favoris = Auth::user()->favoris()->with('favori')->paginate(20);
        return view('favoris.index', compact('favoris'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:piece,vehicle,casse',
            'id' => 'required|integer'
        ]);

        $modelMap = [
            'piece' => \App\Models\Piece::class,
            'vehicle' => \App\Models\Vehicle::class,
            'casse' => \App\Models\User::class,
        ];

        $model = $modelMap[$request->type];
        $item = $model::findOrFail($request->id);

        $favori = Auth::user()->favoris()->where([
            'favori_type' => $model,
            'favori_id' => $request->id
        ])->first();

        if ($favori) {
            $favori->delete();
            $message = 'Retiré des favoris';
            $favoris = false;
        } else {
            Auth::user()->favoris()->create([
                'favori_type' => $model,
                'favori_id' => $request->id
            ]);
            $message = 'Ajouté aux favoris';
            $favoris = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'favoris' => $favoris,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Favoris $favori)
    {
        if ($favori->user_id !== Auth::id()) {
            abort(403);
        }

        $favori->delete();
        return back()->with('success', 'Favori supprimé.');
    }
}
