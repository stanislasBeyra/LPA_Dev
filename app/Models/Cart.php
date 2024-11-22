<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    // Relation avec le modèle Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relation avec le modèle User (si tu as un modèle User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


// $cartItem = Cart::find($id);
// $cartItem->delete(); // Ne supprime pas la ligne, mais met à jour deleted_at
// $activeCarts = Cart::all(); // Récupère uniquement les éléments qui ne sont pas supprimés
// $allCarts = Cart::withTrashed()->get(); // Inclut les éléments qui ont été supprimés
// $cartItem = Cart::withTrashed()->find($id);
// $cartItem->restore(); // Restaure l'élément supprimé
