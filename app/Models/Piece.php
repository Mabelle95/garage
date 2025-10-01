<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Piece extends Model
{
    protected $fillable = [
        'vehicle_id',
        'nom',
        'description',
        'prix',
        'quantite',
        'etat',
        'photos',
        'reference_constructeur',
        'compatible_avec',
        'disponible'
    ];

    protected $casts = [
        'photos' => 'array',
        'compatible_avec' => 'array',
        'disponible' => 'boolean'
    ];

    /**
     * Le véhicule auquel appartient la pièce
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Les articles de panier liés à cette pièce
     */
    public function panierItems(): HasMany
    {
        return $this->hasMany(PanierItem::class);
    }

    /**
     * Les articles de commande liés à cette pièce
     */
    public function commandeItems(): HasMany
    {
        return $this->hasMany(CommandeItem::class);
    }

    /**
     * La casse propriétaire de la pièce (via le véhicule)
     */
    public function casse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'casse_id');
    }
}
