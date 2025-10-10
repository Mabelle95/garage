<?php

namespace App\Http\Controllers;

use App\Models\DemandeEpave;
use App\Models\OffreEpave;
use App\Models\User;
use App\Models\Marque;
use App\Models\Modele;
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

    // ... autres méthodes (index, create, store) ...

    public function show(DemandeEpave $demandeEpave)
    {
        $demandeEpave->load(['user', 'offres.user']);

        // MODIFIÉ : Vérifier s'il peut faire une offre
        // On exclut les offres "en_attente" et "accepte", mais on permet si l'offre est "refuse"
        $offreEnAttenteOuAcceptee = $demandeEpave->offres()
            ->where('user_id', Auth::id())
            ->whereIn('statut', ['en_attente', 'accepte'])
            ->exists();

        $peutFaireOffre = Auth::id() !== $demandeEpave->user_id &&
            $demandeEpave->statut === 'en_attente' &&
            !$offreEnAttenteOuAcceptee;

        // Vérifier si l'utilisateur a une offre refusée
        $offreRefusee = $demandeEpave->offres()
            ->where('user_id', Auth::id())
            ->where('statut', 'refuse')
            ->first();

        return view('demandes-epaves.show', compact('demandeEpave', 'peutFaireOffre', 'offreRefusee'));
    }

    public function faireOffre(Request $request, DemandeEpave $demandeEpave)
    {
        if ($demandeEpave->user_id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas faire d\'offre sur votre propre demande.');
        }

        if ($demandeEpave->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande n\'est plus disponible.');
        }

        $request->validate([
            'prix_offert' => 'required|numeric|min:1',
            'message' => 'nullable|string|max:1000'
        ]);

        // MODIFIÉ : Vérifier seulement les offres en_attente et accepte
        $offreExistante = $demandeEpave->offres()
            ->where('user_id', Auth::id())
            ->whereIn('statut', ['en_attente', 'accepte'])
            ->exists();

        if ($offreExistante) {
            return back()->with('error', 'Vous avez déjà une offre en cours pour cette demande.');
        }

        // Si l'utilisateur a une offre refusée, on la supprime avant de créer la nouvelle
        $demandeEpave->offres()
            ->where('user_id', Auth::id())
            ->where('statut', 'refuse')
            ->delete();

        $offre = OffreEpave::create([
            'demande_epave_id' => $demandeEpave->id,
            'user_id' => Auth::id(),
            'prix_offert' => $request->prix_offert,
            'message' => $request->message,
            'statut' => 'en_attente'
        ]);

        $this->notificationService->nouvelleOffre($demandeEpave->user, $offre);

        return back()->with('success', 'Offre envoyée avec succès.');
    }

    public function retirerOffre(DemandeEpave $demandeEpave, OffreEpave $offre)
    {
        if ($offre->user_id !== Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas retirer cette offre.');
        }

        if ($offre->statut === 'accepte') {
            return back()->with('error', 'Cette offre a déjà été acceptée.');
        }

        if ($offre->demande_epave_id !== $demandeEpave->id) {
            return back()->with('error', 'Offre invalide.');
        }

        $offre->delete();

        return back()->with('success', 'Votre offre a été retirée avec succès.');
    }

    public function accepterOffre(DemandeEpave $demandeEpave, OffreEpave $offre)
    {
        if ($demandeEpave->user_id !== Auth::id()) {
            return back()->with('error', 'Non autorisé.');
        }

        if ($offre->demande_epave_id !== $demandeEpave->id) {
            return back()->with('error', 'Offre invalide.');
        }

        if ($demandeEpave->statut !== 'en_attente') {
            return back()->with('error', 'Demande non disponible.');
        }

        DB::transaction(function() use ($offre, $demandeEpave) {
            $offre->update(['statut' => 'accepte']);
            $demandeEpave->offres()
                ->where('id', '!=', $offre->id)
                ->update(['statut' => 'refuse']);
            $demandeEpave->update(['statut' => 'vendu']);
        });

        $this->notificationService->offreAcceptee($offre->user, $offre);

        $autresOffres = $demandeEpave->offres()
            ->where('id', '!=', $offre->id)
            ->where('statut', 'refuse')
            ->get();

        foreach ($autresOffres as $autreOffre) {
            $this->notificationService->offreRefusee($autreOffre->user, $autreOffre);
        }

        return back()->with('success', 'Offre acceptée avec succès.');
    }

    public function refuserOffre(DemandeEpave $demandeEpave, OffreEpave $offre)
    {
        if ($demandeEpave->user_id !== Auth::id()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à refuser cette offre.');
        }

        if ($offre->demande_epave_id !== $demandeEpave->id) {
            return back()->with('error', 'Offre invalide.');
        }

        if ($demandeEpave->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande n\'est plus disponible.');
        }

        if ($offre->statut === 'accepte') {
            return back()->with('error', 'Cette offre a déjà été acceptée.');
        }

        if ($offre->statut === 'refuse') {
            return back()->with('info', 'Cette offre a déjà été refusée.');
        }

        $offre->update(['statut' => 'refuse']);

        $this->notificationService->offreRefusee($offre->user, $offre);

        return back()->with('success', 'Offre refusée avec succès. L\'acheteur pourra faire une nouvelle offre.');
    }









    public function index(Request $request)
    {
        $user = Auth::user();

        // Récupérer MES demandes
        $mesDemandes = $user->demandesEpaves()
            ->with(['offres.user'])
            ->latest()
            ->paginate(10, ['*'], 'mes_demandes');

        // Récupérer les AUTRES demandes
        $queryAutres = DemandeEpave::with(['user', 'offres'])
            ->where('user_id', '!=', $user->id)
            ->where('statut', 'en_attente');

        // Filtres
        if ($request->filled('marque')) {
            $queryAutres->where('marque', 'like', '%' . $request->marque . '%');
        }

        if ($request->filled('type')) {
            $queryAutres->where('type', $request->type);
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
        $marques = Marque::active()->orderBy('nom')->get();
        return view('demandes-epaves.create', compact('marques'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:vehicule,epave',
            'marque' => 'required|string|max:255',
            'marque_autre' => 'nullable|string|max:255',
            'modele' => 'required|string|max:255',
            'modele_autre' => 'nullable|string|max:255',
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

        // Gérer les nouvelles marques
        if ($request->marque === 'autre' && $request->filled('marque_autre')) {
            $marque = Marque::firstOrCreate(
                ['nom' => $request->marque_autre],
                ['is_active' => true]
            );
            $validated['marque'] = $marque->nom;
        }

        // Gérer les nouveaux modèles
        if ($request->modele === 'autre' && $request->filled('modele_autre')) {
            $validated['modele'] = $request->modele_autre;
        }

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

        // Notifier les utilisateurs
        $utilisateursActifs = User::where('id', '!=', Auth::id())
            ->where('actif', true)
            ->get();

        foreach ($utilisateursActifs as $utilisateur) {
            $this->notificationService->nouvelleDemande($utilisateur, $demande);
        }

        return redirect()->route('demandes-epaves.show', $demande)
            ->with('success', 'Demande créée avec succès.');
    }



    public function edit(DemandeEpave $demandeEpave)
    {
        if ($demandeEpave->user_id !== Auth::id()) {
            abort(403, 'Non autorisé.');
        }

        $marques = Marque::active()->orderBy('nom')->get();
        $modeles = Modele::active()
            ->where('marque_id', optional(Marque::where('nom', $demandeEpave->marque)->first())->id)
            ->orderBy('nom')
            ->get();

        return view('demandes-epaves.edit', compact('demandeEpave', 'marques', 'modeles'));
    }

    public function update(Request $request, DemandeEpave $demandeEpave)
    {
        if ($demandeEpave->user_id !== Auth::id()) {
            abort(403, 'Non autorisé.');
        }

        $validated = $request->validate([
            'type' => 'required|in:vehicule,epave',
            'marque' => 'required|string|max:255',
            'marque_autre' => 'nullable|string|max:255',
            'modele' => 'required|string|max:255',
            'modele_autre' => 'nullable|string|max:255',
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

        // Gérer les nouvelles marques
        if ($request->marque === 'autre' && $request->filled('marque_autre')) {
            $marque = Marque::firstOrCreate(
                ['nom' => $request->marque_autre],
                ['is_active' => true]
            );
            $validated['marque'] = $marque->nom;
        }

        // Gérer les nouveaux modèles
        if ($request->modele === 'autre' && $request->filled('modele_autre')) {
            $validated['modele'] = $request->modele_autre;
        }

        // Upload nouvelles photos
        if ($request->hasFile('photos')) {
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
            ->with('success', 'Demande mise à jour.');
    }

    public function destroy(DemandeEpave $demandeEpave)
    {
        if ($demandeEpave->user_id !== Auth::id()) {
            abort(403, 'Non autorisé.');
        }

        if ($demandeEpave->statut !== 'en_attente') {
            return back()->with('error', 'Suppression impossible.');
        }

        if ($demandeEpave->photos) {
            foreach ($demandeEpave->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $demandeEpave->offres()->delete();
        $demandeEpave->delete();

        return redirect()->route('demandes-epaves.index')
            ->with('success', 'Demande supprimée.');
    }

    // API pour récupérer les modèles d'une marque
    public function getModelesByMarque($marqueId)
    {
        $modeles = Modele::active()
            ->where('marque_id', $marqueId)
            ->orderBy('nom')
            ->get(['id', 'nom']);

        return response()->json($modeles);
    }



}
