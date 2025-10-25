<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommandeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'piece_id',
        'quantite',
        'prix_unitaire',
    ];

    /**
     * La commande à laquelle appartient cet item
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    /**
     * La pièce associée à cet item
     */
    public function piece(): BelongsTo
    {
        return $this->belongsTo(Piece::class, 'piece_id');
    }

    /**
     * Retourne le nom de la pièce ou 'Inconnue' si elle n'existe pas
     */
    public function getNomPieceAttribute(): string
    {
        return $this->piece?->nom ?? 'Inconnue';
    }

    /**
     * Retourne le nom de la casse liée à la pièce ou 'Inconnue' si non disponible
     */
    public function getNomCasseAttribute(): string
    {
        return $this->piece?->vehicle?->casse?->name ?? 'Inconnue';
    }
}
