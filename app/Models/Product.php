<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_name',
        'product_description',
        'stock',
        'price',
        'vendor_id',
        'categorie_id',
        'product_images1',
        'product_images2',
        'product_images3',
    ];

    public function category()
    {
        return $this->belongsTo(productcategories::class, 'categorie_id'); // Assurez-vous que 'category_id' est le nom de la colonne dans la table des produits
    }
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id'); // Ajoutez cette ligne pour la relation avec le vendeur
    }
    public function orderItems()
{
    return $this->hasMany(order_items::class);
}
}
