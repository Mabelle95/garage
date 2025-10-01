<?php

namespace App\Http\Controllers;

use App\Models\DemandeEpave;
use App\Models\OffreEpave;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DemandeEpaveController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Récupérer MES demandes (celles que j'ai créées)
        $mesDemandes = $user->demandesEpaves()
            ->with(['offres.user'])
            ->latest()
            ->paginate(10, ['*'], 'mes_demandes');

        // Récupérer les AUTRES demandes (disponibles à l'achat)
        $queryAutres = DemandeEpave::with(['user', 'offres'])
            ->where('user_id', '!=', $user->id)
            ->where('statut', 'en_attente');

        // Appliquer les filtres
        if ($request->filled('marque')) {
            $queryAutres->where('marque', 'like', '%' . $request->marque . '%');
        }

        if ($request->filled('prix_max')) {
            $queryAutres->where(function($q) use ($request) {
                $q->where('prix_souhaite', '<=', $request->prix_max)
                    ->orWhereNull('prix_souhaite');
            });
        }

        $autresDemandes = $queryAutres->latest()->paginate(10, ['*'], 'autres_demandes');

        return view('demandes-epaves.index', compact('mesDemandes', 'autresDemandes'));
    }

    public function create()
    {
        return view('demandes-epaves.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'numero_chassis' => 'required|string',
            'numero_plaque' => 'required|string',
            'couleur' => 'required|string',
            'carburant' => 'required|in:essence,diesel,hybride,electrique',
            'kilometrage' => 'required|integer|min:0',
            'etat' => 'required|in:bon,moyen,mauvais,epave',
            'prix_souhaite' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'photos.*' => 'nullable|image|max:2048',
            'telephone_contact' => 'required|string',
            'adresse' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['statut'] = 'en_attente';

        // Upload photos
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('demandes-epaves', 'public');
            }
            $validated['photos'] = $photos;
        }

        $demande = DemandeEpave::create($validated);

        // Notifier tous les utilisateurs actifs (clients ET casses)
        $utilisateursActifs = User::where('id', '!=', Auth::id())
            ->where('actif', true)
            ->get();

        foreach ($utilisateursActifs as $utilisateur) {
            $this->notificationService->nouvelleDemande($utilisateur, $demande);
        }

        return redirect()->route('demandes-epaves.show', $demande)
            ->with('success', 'Demande de vente créée avec succès.');
    }

    public function show(DemandeEpave $demandeEpave)
    {
        $demandeEpave->load(['user', 'offres.user']);

        // Peut faire offre si :
        // - Ce n'est pas sa propre demande
        // - La demande est en attente
        // - N'a pas déjà fait d'offre
        $peutFaireOffre = Auth::id() !== $demandeEpave->user_id &&
            $demandeEpave->statut === 'en_attente' &&
            !$demandeEpave->offres()->where('user_id', Auth::id())->exists();

        return view('demandes-epaves.show', compact('demandeEpave', 'peutFaireOffre'));
    }

    public function faireOffre(Request $request, DemandeEpave $demandeEpave)
    {
        // Vérifier que ce n'est pas sa propre demande
        if ($demandeEpave->user_id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas faire d\'offre sur votre propre demande.');
        }

        // Vérifier que la demande est en attente
        if ($demandeEpave->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande n\'est plus disponible.');
        }

        $request->validate([
            'prix_offert' => 'required|numeric|min:1',
            'message' => 'nullable|string|max:1000'
        ]);

        // Vérifier qu'aucune offre n'existe déjà
        if ($demandeEpave->offres()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Vous avez déjà fait une offre pour cette demande.');
        }

        $offre = OffreEpave::create([
            'demande_epave_id' => $demandeEpave->id,
            'user_id' => Auth::id(),
            'prix_offert' => $request->prix_offert,
            'message' => $request->message,
            'statut' => 'en_attente'
        ]);

        // Notifier le propriétaire du véhicule
        $this->notificationService->nouvelleOffre($demandeEpave->user, $offre);

        return back()->with('success', 'Offre envoyée avec succès.');
    }

    public function retirerOffre(DemandeEpave $demandeEpave, OffreEpave $offre)
    {
        // Vérifier que c'est bien son offre
        if ($offre->user_id !== Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas retirer cette offre.');
        }

        // Vérifier que l'offre n'a pas déjà été acceptée
        if ($offre->statut === 'accepte') {
            return back()->with('error', 'Cette offre a déjà été acceptée et ne peut plus être retirée.');
        }

        // Vérifier que l'offre appartient bien à cette demande
        if ($offre->demande_epave_id !== $demandeEpave->id) {
            return back()->with('error', 'Offre invalide.');
        }

        $offre->delete();

        return back()->with('success', 'Votre offre a été retirée avec succès.');
    }

    public function accepterOffre(DemandeEpave $demandeEpave, OffreEpave $offre)
    {
        // Vérifier que c'est bien le propriétaire de la demande
        if ($demandeEpave->user_id !== Auth::id()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à accepter cette offre.');
        }

        // Vérifier que l'offre appartient à cette demande
        if ($offre->demande_epave_id !== $demandeEpave->id) {
            return back()->with('error', 'Offre invalide.');
        }

        // Vérifier que la demande est encore en attente
        if ($demandeEpave->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande n\'est plus disponible.');
        }

        DB::transaction(function() use ($offre, $demandeEpave) {
            // Accepter l'offre
            $offre->update(['statut' => 'accepte']);

            // Refuser les autres offres liées à cette demande
            $demandeEpave->offres()
                ->where('id', '!=', $offre->id)
                ->update(['statut' => 'refuse']);

            // Mettre à jour le statut de la demande
            $demandeEpave->update(['statut' => 'vendu']);
        });

        // Notifier l'acheteur que son offre a été acceptée
        $this->notificationService->offreAcceptee($offre->user, $offre);

        // Notifier les autres personnes que leurs offres ont été refusées
        $autresOffres = $demandeEpave->offres()
            ->where('id', '!=', $offre->id)
            ->where('statut', 'refuse')
            ->get();

        foreach ($autresOffres as $autreOffre) {
            $this->notificationService->offreRefusee($autreOffre->user, $autreOffre);
        }

        return back()->with('success', 'Offre acceptée avec succès. La transaction est maintenant finalisée.');
    }

    // MODIFIÉ : Accessible à tous pour modifier leur propre demande
    public function edit(DemandeEpave $demandeEpave)
    {
        // Vérifier que c'est bien le propriétaire
        if ($demandeEpave->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas modifier cette demande car vous n\'en êtes pas le propriétaire.');
        }

        // Permet la modification même si le statut n'est pas "en_attente"
        // Mais on peut ajouter des restrictions si nécessaire
        return view('demandes-epaves.edit', compact('demandeEpave'));
    }

    // MODIFIÉ : Accessible à tous pour modifier leur propre demande
    public function update(Request $request, DemandeEpave $demandeEpave)
    {
        // Vérifier que c'est bien le propriétaire
        if ($demandeEpave->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas modifier cette demande car vous n\'en êtes pas le propriétaire.');
        }

        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'couleur' => 'required|string',
            'carburant' => 'required|in:essence,diesel,hybride,electrique',
            'kilometrage' => 'required|integer|min:0',
            'etat' => 'required|in:bon,moyen,mauvais,epave',
            'prix_souhaite' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'photos.*' => 'nullable|image|max:2048',
            'telephone_contact' => 'required|string',
            'adresse' => 'required|string',
        ]);

        // Upload nouvelles photos
        if ($request->hasFile('photos')) {
            // Supprimer les anciennes photos
            if ($demandeEpave->photos) {
                foreach ($demandeEpave->photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('demandes-epaves', 'public');
            }
            $validated['photos'] = $photos;
        }

        $demandeEpave->update($validated);

        return redirect()->route('demandes-epaves.show', $demandeEpave)
            ->with('success', 'Demande mise à jour avec succès.');
    }

    public function destroy(DemandeEpave $demandeEpave)
    {
        // Vérifier que c'est bien le propriétaire
        if ($demandeEpave->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas supprimer cette demande car vous n\'en êtes pas le propriétaire.');
        }

        // Ne peut supprimer que si en attente
        if ($demandeEpave->statut !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez supprimer cette demande que si elle est en attente.');
        }

        // Supprimer les photos
        if ($demandeEpave->photos) {
            foreach ($demandeEpave->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        // Supprimer les offres associées
        $demandeEpave->offres()->delete();

        $demandeEpave->delete();

        return redirect()->route('demandes-epaves.index')
            ->with('success', 'Demande supprimée avec succès.');
    }
}
