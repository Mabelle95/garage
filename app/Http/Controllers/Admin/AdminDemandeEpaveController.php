<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeEpave;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDemandeEpaveController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Afficher la liste de toutes les demandes d'épaves
     */
    public function index(Request $request)
    {
        $query = DemandeEpave::with(['user', 'offres']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('marque')) {
            $query->where('marque', 'like', '%' . $request->marque . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('marque', 'like', "%{$search}%")
                    ->orWhere('modele', 'like', "%{$search}%")
                    ->orWhere('numero_chassis', 'like', "%{$search}%")
                    ->orWhere('numero_plaque', 'like', "%{$search}%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $demandes = $query->latest()->paginate(15);

        // Statistiques
        $stats = [
            'total' => DemandeEpave::count(),
            'en_attente' => DemandeEpave::where('statut', 'en_attente')->count(),
            'vendu' => DemandeEpave::where('statut', 'vendu')->count(),
            'annule' => DemandeEpave::where('statut', 'annule')->count(),
            'rejete' => DemandeEpave::where('statut', 'rejete')->count(),
        ];

        return view('admin.demandes-epaves.index', compact('demandes', 'stats'));
    }

    /**
     * Afficher les détails d'une demande d'épave
     */
    public function show(DemandeEpave $demandeEpave)
    {
        $demandeEpave->load(['user', 'offres.user']);

        return view('admin.demandes-epaves.show', compact('demandeEpave'));
    }

    /**
     * Confirmer/Approuver une demande d'épave
     */
    public function confirmer(DemandeEpave $demandeEpave)
    {
        if ($demandeEpave->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande ne peut pas être confirmée.');
        }

        DB::transaction(function() use ($demandeEpave) {
            $demandeEpave->update([
                'statut' => 'en_attente',
                'approuve_admin' => true,
                'approuve_admin_at' => now(),
            ]);

            // Notifier l'utilisateur
            $this->notificationService->demandeApprouvee($demandeEpave->user, $demandeEpave);

            // Notifier toutes les casses
            $casses = User::where('role', 'casse')
                ->where('id', '!=', $demandeEpave->user_id)
                ->where('approved', true)
                ->get();

            foreach ($casses as $casse) {
                $this->notificationService->nouvelleDemande($casse, $demandeEpave);
            }
        });

        return back()->with('success', 'Demande confirmée et publiée avec succès.');
    }

    /**
     * Rejeter une demande d'épave
     */
    public function rejeter(Request $request, DemandeEpave $demandeEpave)
    {
        $request->validate([
            'raison_rejet' => 'required|string|max:1000',
        ]);

        if (in_array($demandeEpave->statut, ['vendu', 'rejete'])) {
            return back()->with('error', 'Cette demande ne peut pas être rejetée.');
        }

        DB::transaction(function() use ($demandeEpave, $request) {
            $demandeEpave->update([
                'statut' => 'rejete',
                'raison_rejet' => $request->raison_rejet,
                'rejete_at' => now(),
            ]);

            // Notifier l'utilisateur
            $this->notificationService->demandeRejetee($demandeEpave->user, $demandeEpave, $request->raison_rejet);
        });

        return back()->with('success', 'Demande rejetée avec succès.');
    }

    /**
     * Supprimer une demande d'épave (admin uniquement)
     */
    public function destroy(DemandeEpave $demandeEpave)
    {
        // Supprimer les photos
        if ($demandeEpave->photos) {
            foreach ($demandeEpave->photos as $photo) {
                \Storage::disk('public')->delete($photo);
            }
        }

        // Supprimer les offres associées
        $demandeEpave->offres()->delete();

        // Supprimer la demande
        $demandeEpave->delete();

        return redirect()->route('admin.demandes-epaves.index')
            ->with('success', 'Demande supprimée avec succès.');
    }

    /**
     * Statistiques détaillées
     */
    public function statistiques()
    {
        $stats = [
            // Demandes par statut
            'par_statut' => DemandeEpave::select('statut', DB::raw('count(*) as total'))
                ->groupBy('statut')
                ->pluck('total', 'statut')
                ->toArray(),

            // Demandes par type
            'par_type' => DemandeEpave::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->pluck('total', 'type')
                ->toArray(),

            // Demandes par mois (12 derniers mois)
            'par_mois' => DemandeEpave::select(
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('count(*) as total')
            )
                ->where('created_at', '>=', now()->subYear())
                ->groupBy('annee', 'mois')
                ->orderBy('annee', 'desc')
                ->orderBy('mois', 'desc')
                ->get(),

            // Top utilisateurs
            'top_utilisateurs' => DemandeEpave::select('user_id', DB::raw('count(*) as total'))
                ->groupBy('user_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->with('user')
                ->get(),

            // Prix moyen demandé
            'prix_moyen' => DemandeEpave::whereNotNull('prix_souhaite')->avg('prix_souhaite'),

            // Nombre moyen d'offres par demande
            'offres_moyennes' => DemandeEpave::withCount('offres')->avg('offres_count'),

            // Taux de conversion
            'taux_conversion' => [
                'total' => DemandeEpave::count(),
                'vendu' => DemandeEpave::where('statut', 'vendu')->count(),
            ],
        ];

        return view('admin.demandes-epaves.statistiques', compact('stats'));
    }

    /**
     * Exportation CSV
     */
    public function export(Request $request)
    {
        $query = DemandeEpave::with(['user', 'offres']);

        // Appliquer les mêmes filtres que la liste
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $demandes = $query->get();

        $filename = 'demandes_epaves_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($demandes) {
            $file = fopen('php://output', 'w');

            // En-têtes
            fputcsv($file, [
                'ID',
                'Date',
                'Utilisateur',
                'Email',
                'Type',
                'Marque',
                'Modèle',
                'Année',
                'Châssis',
                'Plaque',
                'Prix souhaité',
                'Statut',
                'Nombre offres',
            ]);

            // Données
            foreach ($demandes as $demande) {
                fputcsv($file, [
                    $demande->id,
                    $demande->created_at->format('Y-m-d H:i'),
                    $demande->user->name,
                    $demande->user->email,
                    $demande->type,
                    $demande->marque,
                    $demande->modele,
                    $demande->annee,
                    $demande->numero_chassis,
                    $demande->numero_plaque,
                    $demande->prix_souhaite ?? 'N/A',
                    $demande->statut,
                    $demande->offres->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
