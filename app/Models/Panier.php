<?php




// app/Models/Panier.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panier extends Model
{
    protected $fillable = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PanierItem::class);
    }

    public function getTotal()
    {
        return $this->items->sum(function($item) {
            return $item->quantite * $item->piece->prix;
        });
    }
}
