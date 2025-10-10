<?php
// app/Models/NomPiece.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NomPiece extends Model
{
    protected $fillable = ['nom', 'categorie', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nom', 'like', '%' . $search . '%');
    }
}

// Mettre à jour le modèle Piece.php - Ajouter ces lignes

// Dans les fillable, ajouter :
// 'nom_piece_id',

// Ajouter cette relation :
// public function nomPiece(): BelongsTo
// {
//     return $this->belongsTo(NomPiece::class);
// }
