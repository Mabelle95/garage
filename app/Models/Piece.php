<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Piece extends Model
{
    protected $fillable = [
        'marque_piece',
        'modele_piece',
        'nom',
        'description',
        'prix',
        'quantite',
        'etat',
        'photos',
        'reference_constructeur',
        'compatible_avec',
        'disponible',
        'ville',
        'user_id',
    ];

    protected $casts = [
        'photos' => 'array',
        'disponible' => 'boolean'
    ];

    public function casse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commandeItems(): HasMany
    {
        return $this->hasMany(CommandeItem::class);

    }
    // app/Models/Piece.php
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }


}
