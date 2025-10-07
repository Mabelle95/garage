<?php

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

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'latitude',
        'longitude'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    // -----------------------------
    // Vérification de rôle
    // -----------------------------
    public function isCasse(): bool
    {
        return $this->role === UserRole::CASSE;
    }

    public function isClient(): bool
    {
        return $this->role === UserRole::CLIENT;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    // -----------------------------
    // Relations
    // -----------------------------
    // Véhicules d'une casse
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'casse_id');
    }

    // Pièces via les véhicules


    public function pieces()
    {
        return $this->hasMany(Piece::class);
    }

    // Commandes passées par l'utilisateur
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    // Panier du client
    public function panier(): HasOne
    {
        return $this->hasOne(Panier::class, 'user_id');
    }

    public function panierItems()
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











    // Notifications
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Demandes d'épaves
    public function demandesEpaves(): HasMany
    {
        return $this->hasMany(DemandeEpave::class, 'user_id');
    }

    // Favoris
    public function favoris(): HasMany
    {
        return $this->hasMany(Favoris::class, 'user_id');
    }

    // Ventes d'épaves
    public function venteEpaves(): HasMany
    {
        return $this->hasMany(VenteEpave::class, 'client_id');
    }

    // Recherches sauvegardées
    public function recherchesSauvegardees(): HasMany
    {
        return $this->hasMany(RechercheSauvegardee::class, 'client_id');
    }

    // -----------------------------
    // Méthodes utilitaires
    // -----------------------------
    public function getTotalPanier()
    {
        return $this->panierItems()->with('piece')->get()->sum(fn($item) => $item->quantite * $item->piece->prix);
    }

    public function getNombrePiecesPanier()
    {
        return $this->panierItems()->sum('quantite');
    }

    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) return null;

        $earthRadius = 6371; // km
        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    // -----------------------------
    // Booted
    // -----------------------------
    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->isClient()) {
                $user->panier()->create();
            }
        });
    }
}
