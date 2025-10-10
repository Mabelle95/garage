<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    protected $fillable = [
        'user_id',             // client qui passe la commande
        'casse_id',            // casse qui fournit la pièce
        'numero_commande',
        'statut',
        'total',
        'adresse_livraison',
        'telephone_livraison',
        'mode_paiement',
        'statut_paiement',
        'notes'
    ];

    /**
     * Le client qui a passé la commande
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * La casse qui fournit la pièce
     */
    public function casse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'casse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Les articles de la commande
     */
    public function items(): HasMany
    {
        return $this->hasMany(CommandeItem::class, 'commande_id');
    }

    /**
     * Les notifications liées à la commande
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'commande_id');
    }
}
