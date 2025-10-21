<?php
// app/Models/User.php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'adresse',
        'code_postal',
        'role',
        'ville',
        'latitude',
        'telephone',

        'flooz_number',
        'mixx_number',

        'longitude',
        'approved', // 👈 Ajouter ici
        'approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'approved_at' => 'datetime', // 👈 Ajouter ici

        ];
    }

    // -----------------------------
    // Vérification de rôle
    // -----------------------------

    /**
     * Vérifie si l'utilisateur est une casse
     */
    public function isCasse(): bool
    {
        return $this->role === UserRole::CASSE;
    }

    /**
     * Vérifie si l'utilisateur est un client
     */
    public function isClient(): bool
    {
        return $this->role === UserRole::CLIENT;
    }

    /**
     * Vérifie si l'utilisateur est un admin
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isCompleted(): bool
    {
        return $this->telephone && $this->adresse && $this->email && $this->ville;
    }

    // -----------------------------
    // Relations
    // -----------------------------

    /**
     * Véhicules d'une casse
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'casse_id');
    }

    /**
     * Pièces détachées de la casse
     */
    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class, 'user_id');
    }

    /**
     * Commandes passées par l'utilisateur (en tant que client)
     */
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'user_id');
    }

    /**
     * Commandes reçues par la casse (via les pièces)
     */
    public function commandesRecues(): HasManyThrough
    {
        return $this->hasManyThrough(
            Commande::class,
            Piece::class,
            'user_id',      // clé étrangère sur pieces
            'id',           // clé primaire sur commandes
            'id',           // clé locale user
            'piece_id'      // clé locale pieces
        );
    }

    /**
     * Panier du client
     */
    public function panier(): HasOne
    {
        return $this->hasOne(Panier::class, 'user_id');
    }

    /**
     * Articles dans le panier via la relation hasManyThrough
     */
    public function panierItems(): HasManyThrough
    {
        return $this->hasManyThrough(
            PanierItem::class,
            Panier::class,
            'user_id',   // clé étrangère sur panier
            'panier_id', // clé étrangère sur panier_items
            'id',        // clé locale User
            'id'         // clé locale Panier
        );
    }

    /**
     * Notifications de l'utilisateur
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * Demandes d'épaves créées par l'utilisateur
     */
    public function demandesEpaves(): HasMany
    {
        return $this->hasMany(DemandeEpave::class, 'user_id');
    }

    /**
     * Favoris de l'utilisateur
     */
    public function favoris(): HasMany
    {
        return $this->hasMany(Favoris::class, 'user_id');
    }

    /**
     * Ventes d'épaves
     */
    public function venteEpaves(): HasMany
    {
        return $this->hasMany(VenteEpave::class, 'client_id');
    }

    /**
     * Recherches sauvegardées
     */
    public function recherchesSauvegardees(): HasMany
    {
        return $this->hasMany(RechercheSauvegardee::class, 'client_id');
    }

    // -----------------------------
    // Méthodes utilitaires pour le panier
    // -----------------------------

    /**
     * Calcule le total du panier
     */
    public function getTotalPanier(): float
    {
        return $this->panierItems()
            ->with('piece')
            ->get()
            ->sum(fn($item) => $item->quantite * $item->piece->prix);
    }

    /**
     * Retourne le nombre total de pièces dans le panier
     */
    public function getNombrePiecesPanier(): int
    {
        return $this->panierItems()->sum('quantite');
    }

    /**
     * Retourne le nombre de lignes dans le panier
     */
    public function getNombreLignesPanier(): int
    {
        return $this->panierItems()->count();
    }

    /**
     * Vérifie si le panier est vide
     */
    public function panierEstVide(): bool
    {
        return $this->getNombrePiecesPanier() === 0;
    }

    /**
     * Vide complètement le panier
     */
    public function viderPanier(): void
    {
        $this->panierItems()->delete();
    }

    /**
     * Vérifie si une pièce est dans le panier
     */
    public function pieceEstDansPanier(int $pieceId): bool
    {
        return $this->panierItems()
            ->where('piece_id', $pieceId)
            ->exists();
    }

    // -----------------------------
    // Méthodes utilitaires pour les notifications
    // -----------------------------

    /**
     * Retourne le nombre de notifications non lues
     */
    public function getUnreadNotificationsCount(): int
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    /**
     * Retourne les notifications non lues
     */
    public function getUnreadNotifications()
    {
        return $this->notifications()
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Retourne les notifications récentes (lues et non lues)
     */
    public function getRecentNotifications(int $limit = 10)
    {
        return $this->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Marque toutes les notifications comme lues
     */
    public function markAllNotificationsAsRead(): void
    {
        $this->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Crée une nouvelle notification
     */
    public function notify(string $titre, string $message, ?string $type = 'info', ?string $url = null): void
    {
        $this->notifications()->create([
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
            'url' => $url,
        ]);
    }

    // -----------------------------
    // Méthodes de géolocalisation
    // -----------------------------

    /**
     * Vérifie si l'utilisateur a une position GPS
     */
    public function hasLocation(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Définit la localisation de l'utilisateur
     */
    public function setLocation(float $latitude, float $longitude): void
    {
        $this->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    /**
     * Calcule la distance entre l'utilisateur et un point GPS donné
     *
     * @param float $latitude
     * @param float $longitude
     * @return float|null Distance en kilomètres ou null si pas de coordonnées
     */
    public function getDistanceFrom(float $latitude, float $longitude): ?float
    {
        if (!$this->hasLocation()) {
            return null;
        }

        $earthRadius = 6371; // Rayon de la Terre en km

        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
                pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
            ));

        return round($angle * $earthRadius, 2);
    }

    /**
     * Retourne la distance formatée avec l'unité
     */
    public function getDistanceFromFormatted(float $latitude, float $longitude): string
    {
        $distance = $this->getDistanceFrom($latitude, $longitude);

        if (is_null($distance)) {
            return 'Distance inconnue';
        }

        if ($distance < 1) {
            return round($distance * 1000) . ' m';
        }

        return round($distance, 1) . ' km';
    }

    /**
     * Retourne les casses les plus proches
     */
    public static function getCassesProches(?float $latitude = null, ?float $longitude = null, int $limit = 10)
    {
        $casses = self::casses()
            ->actifs()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        if (!$latitude || !$longitude) {
            return $casses->take($limit);
        }

        // Trier par distance
        return $casses->sortBy(function ($casse) use ($latitude, $longitude) {
            return $casse->getDistanceFrom($latitude, $longitude);
        })->take($limit);
    }

    // -----------------------------
    // Méthodes pour les statistiques (Casse)
    // -----------------------------

    /**
     * Retourne le nombre total de pièces
     */
    public function getNombreTotalPieces(): int
    {
        return $this->pieces()->count();
    }

    /**
     * Retourne le nombre total de pièces en stock
     */
    public function getTotalPiecesEnStock(): int
    {
        return $this->pieces()->sum('quantite');
    }

    /**
     * Retourne le nombre de pièces disponibles
     */
    public function getNombrePiecesDisponibles(): int
    {
        return $this->pieces()
            ->where('disponible', true)
            ->where('quantite', '>', 0)
            ->count();
    }

    /**
     * Retourne le nombre de pièces en rupture
     */
    public function getNombrePiecesEnRupture(): int
    {
        return $this->pieces()
            ->where('quantite', 0)
            ->count();
    }

    /**
     * Retourne le nombre de pièces avec stock faible (≤ 3)
     */
    public function getNombrePiecesStockFaible(): int
    {
        return $this->pieces()
            ->whereBetween('quantite', [1, 3])
            ->count();
    }

    /**
     * Retourne la valeur totale du stock
     */
    public function getValeurTotaleStock(): float
    {
        return $this->pieces()
            ->get()
            ->sum(fn($piece) => $piece->quantite * $piece->prix);
    }

    /**
     * Retourne le nombre de véhicules
     */
    public function getNombreVehicules(): int
    {
        return $this->vehicles()->count();
    }

    /**
     * Retourne le chiffre d'affaires total
     */
    public function getChiffreAffaires(): float
    {
        return $this->commandes()
            ->whereIn('statut', ['livree', 'terminee'])
            ->sum('montant_total');
    }

    /**
     * Retourne le CA du mois en cours
     */
    public function getChiffreAffairesMois(): float
    {
        return $this->commandes()
            ->whereIn('statut', ['livree', 'terminee'])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('montant_total');
    }

    /**
     * Retourne les statistiques globales de la casse
     */
    public function getStatistiquesCasse(): array
    {
        return [
            'total_pieces' => $this->getNombreTotalPieces(),
            'stock_total' => $this->getTotalPiecesEnStock(),
            'pieces_disponibles' => $this->getNombrePiecesDisponibles(),
            'pieces_rupture' => $this->getNombrePiecesEnRupture(),
            'stock_faible' => $this->getNombrePiecesStockFaible(),
            'valeur_stock' => $this->getValeurTotaleStock(),
            'nombre_vehicules' => $this->getNombreVehicules(),
            'ca_total' => $this->getChiffreAffaires(),
            'ca_mois' => $this->getChiffreAffairesMois(),
            'commandes_en_cours' => $this->getNombreCommandesEnCours(),
        ];
    }

    // -----------------------------
    // Méthodes pour les commandes
    // -----------------------------

    /**
     * Retourne le nombre total de commandes
     */
    public function getNombreTotalCommandes(): int
    {
        return $this->commandes()->count();
    }

    /**
     * Retourne le nombre de commandes en cours
     */
    public function getNombreCommandesEnCours(): int
    {
        return $this->commandes()
            ->whereIn('statut', ['en_attente', 'confirmee', 'en_preparation', 'expedie'])
            ->count();
    }

    /**
     * Retourne le nombre de commandes livrées
     */
    public function getNombreCommandesLivrees(): int
    {
        return $this->commandes()
            ->whereIn('statut', ['livree', 'terminee'])
            ->count();
    }

    /**
     * Retourne les commandes récentes
     */
    public function getCommandesRecentes(int $limit = 5)
    {
        return $this->commandes()
            ->with(['items.piece'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Retourne la dernière commande
     */
    public function getDerniereCommande()
    {
        return $this->commandes()
            ->with(['items.piece'])
            ->latest()
            ->first();
    }

    // -----------------------------
    // Méthodes pour les favoris
    // -----------------------------

    /**
     * Ajoute une pièce aux favoris
     */
    public function ajouterAuxFavoris(int $pieceId): void
    {
        if (!$this->favoris()->where('piece_id', $pieceId)->exists()) {
            $this->favoris()->create(['piece_id' => $pieceId]);
        }
    }

    /**
     * Retire une pièce des favoris
     */
    public function retirerDesFavoris(int $pieceId): void
    {
        $this->favoris()->where('piece_id', $pieceId)->delete();
    }

    /**
     * Vérifie si une pièce est en favoris
     */
    public function pieceEstEnFavoris(int $pieceId): bool
    {
        return $this->favoris()->where('piece_id', $pieceId)->exists();
    }

    /**
     * Retourne le nombre de favoris
     */
    public function getNombreFavoris(): int
    {
        return $this->favoris()->count();
    }

    // -----------------------------
    // Accesseurs (Getters)
    // -----------------------------

    /**
     * Retourne le nom du rôle en français
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            UserRole::ADMIN => 'Administrateur',
            UserRole::CASSE => 'Casse Auto',
            UserRole::CLIENT => 'Client',
            default => 'Utilisateur',
        };
    }

    /**
     * Retourne les initiales de l'utilisateur
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Retourne l'avatar de l'utilisateur (gravatar)
     */
    public function getAvatarUrlAttribute(): string
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    /**
     * Retourne le prénom de l'utilisateur
     */
    public function getPrenomAttribute(): string
    {
        return explode(' ', $this->name)[0];
    }

    /**
     * Vérifie si l'utilisateur est vérifié
     */
    public function getIsVerifiedAttribute(): bool
    {
        return !is_null($this->email_verified_at);
    }

    // -----------------------------
    // Scopes
    // -----------------------------

    /**
     * Scope pour filtrer les casses
     */
    public function scopeCasses($query)
    {
        return $query->where('role', UserRole::CASSE);
    }

    /**
     * Scope pour filtrer les clients
     */
    public function scopeClients($query)
    {
        return $query->where('role', UserRole::CLIENT);
    }

    /**
     * Scope pour filtrer les admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', UserRole::ADMIN);
    }

    /**
     * Scope pour filtrer par ville
     */
    public function scopeInVille($query, string $ville)
    {
        return $query->where('ville', $ville);
    }

    /**
     * Scope pour les utilisateurs actifs (avec email vérifié)
     */
    public function scopeActifs($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope pour rechercher un utilisateur
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('ville', 'like', "%{$search}%");
        });
    }

    /**
     * Scope pour les utilisateurs créés récemment
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // -----------------------------
    // Events (Boot)
    // -----------------------------

    /**
     * Boot du modèle
     */
    protected static function booted(): void
    {
        // Créer automatiquement un panier pour les clients
        static::created(function (User $user) {
            if ($user->isClient()) {
                $user->panier()->create();
            }
        });

        // Log lors de la suppression
        static::deleting(function (User $user) {
            \Log::info("Suppression de l'utilisateur: {$user->name} (ID: {$user->id})");
        });
    }


    // À ajouter dans app/Models/User.php

    /**
     * Obtenir le nombre de notifications non lues
     */
    public function getNombreNotificationsNonLues(): int
    {
        return $this->notifications()->where('lu', false)->count();
    }
}
