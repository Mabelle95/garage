<?php
// app/Models/Modele.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modele extends Model
{
    protected $fillable = ['marque_id', 'nom', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function marque(): BelongsTo
    {
        return $this->belongsTo(Marque::class);
    }

    public function pieces(): HasMany
    {
        return $this->hasMany(Piece::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForMarque($query, $marqueId)
    {
        return $query->where('marque_id', $marqueId);
    }
}
