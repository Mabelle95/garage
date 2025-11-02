<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\CommandeItem;
use App\Models\Panier;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Liste des commandes
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isCasse()) {
            // Filtrer les commandes qui contiennent au moins une pièce appartenant à cette casse
            $query = Commande::whereHas('items.piece', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['user', 'items.piece']);

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

            $commandes = $query->latest()->paginate(10);
        } else {
            $query = $user->commandes()->with(['items.piece']);
            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }
            $commandes = $query->latest()->paginate(10);
        }

        return view('commandes.index', compact('commandes'));
    }

    // Formulaire de création de commande
    public function create(User $casseUserId)
    {
        $panier = Auth::user()->panier()->with(['items.piece'])->first();
        $casse_id = $panier->items[0]->piece->user_id;
        $casse = User::query()->where('id', $casse_id)->first();

        // dd($casseUserId->id);
        // $commande = Panier::query()->with(['items'])->get();

        // dd($panier->);

        $panierCasse = [];
        $totalCasse = 0;

        foreach ($panier->items as $item) {
            // dd($item->piece->user_id);
            if ($item->piece->user_id === $casseUserId->id) {
                $panierCasse[] = $item;
                $totalCasse += $item->piece->prix * $item->quantite;
            }
        }


        if (!$panier || $panier->items->isEmpty()) {
            return redirect()->route('panier.index')
                ->with('error', 'Votre panier est vide.');
        }

        foreach ($panier->items as $item) {
            if (!$item->piece->disponible || $item->piece->quantite < $item->quantite) {
                return redirect()->route('panier.index')
                    ->with('error', "La pièce {$item->piece->nom} n'est plus disponible en quantité suffisante.");
            }
        }

        return view('commandes.create', compact('panier', 'casse', 'panierCasse', 'totalCasse'));
    }
    public function confirme(Request $request) {
        // dd($request->all());
        $client = $request->all();

        $montant = $request->panier_total * env('TAUX_PAIEMENT', 0.05);

        $casse = json_decode($request->casse);
        $total = $request->panier_total;
        $network = $request->mode_paiement;
        
        $payWay = null;

        // dd($casse->flooz_number);
        if ($network === 'flooz') {
            $payWay = $casse->flooz_number;
        } else {
            $payWay = $casse->mixx_number;
        }

        $shortCode = null;

        if ($network === 'flooz') {
            $shortCode = "*155*1*1*$payWay*$montant#";
        } else {
            $shortCode = "*145*1*$montant*$payWay#";
        }

        // dd($client);

        // dd("*145*1*$montant*$request->mode_paiement#", $request);
        return view('commande.comfirme', compact('shortCode', 'montant', 'payWay', 'total', 'client', 'casse'));
    }

    // Stocker une commande
    public function store(Request $request)
    {
        // dd($request->input('casse'));
        $casseId = $request->input('casse');
        $totalCmdCasse = 0;

        $request->validate([
            'adresse_livraison' => 'required|string',
            'telephone_livraison' => 'required|string',
            // 'mode_paiement' => 'required|in:carte_bancaire,paypal,virement,especes',
            'mode_paiement' => 'required',
            'notes' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $panier = Auth::user()->panier()->with(['items.piece'])->first();

        if (!$panier || $panier->items->isEmpty()) {
            return redirect()->route('panier.index')
                ->with('error', 'Votre panier est vide.');
        }

        foreach ($panier->items as $item) {
            if (!$item->piece->disponible || $item->piece->quantite < $item->quantite) {
                return back()->with('error', "Stock insuffisant pour la pièce: {$item->piece->nom}");
            }
            // dd($item->piece->user_id);

            if ($item->piece->user_id === (int) $request->input('casse')) {
                    $totalCmdCasse += $item->piece->prix * $item->quantite;
                }


        }

        $commande = null;

        DB::transaction(function() use ($request, $panier, &$commande, $totalCmdCasse) {
            $commande = Commande::create([
                'user_id' => Auth::id(),
                'numero_commande' => 'CMD-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'statut' => 'en_attente',
                'total' => $totalCmdCasse,
                // 'total' => $panier->getTotal(),
                'adresse_livraison' => $request->adresse_livraison,
                'telephone_livraison' => $request->telephone_livraison,
                'mode_paiement' => $request->mode_paiement,
                'statut_paiement' => 'en_attente',
                'notes' => $request->notes,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            foreach ($panier->items as $item) {
                // dd($item->piece->user_id);
                // dd($item->piece->user_id === $request->input('casse'), $item->piece->user_id, (int) $request->input('casse'));
                if ($item->piece->user_id === (int) $request->input('casse')) {
                    CommandeItem::create([
                        'commande_id' => $commande->id,
                        'piece_id' => $item->piece_id,
                        'quantite' => $item->quantite,
                        'prix_unitaire' => $item->piece->prix
                    ]);

                    $item->delete();
                }

                // Mettre à jour le stock
                $item->piece->decrement('quantite', $item->quantite);
                if ($item->piece->quantite <= 0) {
                    $item->piece->update(['disponible' => false]);
                }

                // Notification à la casse propriétaire de la pièce
                $this->notificationService->nouvelleCommande($item->piece->user, $commande);
            }

            // Vider le panier
            // $panier->items()->delete();

            // dd($item->piece->user);

            // Notification à l'utilisateur client
            $this->notificationService->commandeCreee(Auth::user(), $commande);
        });

        $info = $item->piece->user->telephone ?  $item->piece->user->telephone : "90992020 ou 99440449";

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Commande créée avec succès.');
        // return redirect()->route('payment.fedapay.index', $commande)
        //     ->with('success', 'Commande créée avec succès.');
            // ->with('success', 'Commande créée avec succès. veuillez completer l\'operation par un depot sur le ' . $info);
    }

    // Afficher une commande
    public function show(Commande $commande)
    {
        $this->authorize('view', $commande);
        $commande->load(['user', 'items.piece']);
        return view('commandes.show', compact('commande'));
    }

    // Formulaire pour modifier le statut
    public function editStatut(Commande $commande)
    {
        return view('commandes.edit', compact('commande'));
    }

    // Mise à jour du statut pour la casse
    public function updateStatut(Request $request, Commande $commande)
    {
        $this->authorize('updateStatut', $commande);

        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_preparation,expedie,livree,annulee',
            'commentaire' => 'nullable|string'
        ]);

        $ancienStatut = $commande->statut;
        $commande->update(['statut' => $request->statut]);

        $this->notificationService->statutCommandeChange($commande->user, $commande, $ancienStatut, $request->statut);

        return back()->with('success', 'Statut de la commande mis à jour.');
    }

    // Annuler une commande
    public function annuler(Commande $commande)
    {
        $this->authorize('annuler', $commande);

        if (!in_array($commande->statut, ['en_attente', 'confirmee'])) {
            return back()->with('error', 'Cette commande ne peut plus être annulée.');
        }

        // Vérifier que toutes les pièces de la commande existent encore
        $commande->load('items.piece');
        $piecesSupprimees = $commande->items->filter(function($item) {
            return is_null($item->piece);
        });

        if ($piecesSupprimees->isNotEmpty()) {
            return back()->with('error',
                'Impossible d\'annuler cette commande car certaines pièces ne sont plus disponibles dans le catalogue. ' .
                'Veuillez contacter le service client pour obtenir de l\'aide.'
            );
        }

        DB::transaction(function() use ($commande) {
            foreach ($commande->items as $item) {
                // Vérifier à nouveau que la pièce existe avant de mettre à jour le stock
                if ($item->piece) {
                    $item->piece->increment('quantite', $item->quantite);
                    $item->piece->update(['disponible' => true]);
                }
            }

            $commande->update(['statut' => 'annulee']);

            $casses = $commande->items->map(function($item) {
                return $item->piece ? $item->piece->user : null;
            })->filter()->unique();

            foreach ($casses as $casse) {
                $this->notificationService->commandeAnnulee($casse, $commande);
            }
        });

        return back()->with('success', 'Commande annulée avec succès.');
    }

    // Mise à jour de l'adresse / géolocalisation
    public function updateAdresse(Request $request, Commande $commande)
    {
        $this->authorize('update', $commande);

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $adresseGeo = "Lat: {$request->latitude}, Lon: {$request->longitude}";
        $commande->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'adresse_livraison' => $adresseGeo . "\n" . $commande->adresse_livraison
        ]);

        return back()->with('success', 'Adresse de livraison mise à jour avec votre géolocalisation.');
    }

    public function delete(Commande $commande)
    {
        // $this->authorize('delete', $commande);
        // dd($commande);

        if ($commande->statut !== 'annulee') {
            return back()->with('error', 'Seules les commandes annulées peuvent être supprimées.');
        }

        $commande->delete();

        return redirect()->route('commandes.index')->with('success', 'Commande supprimée avec succès.');
    }
}
