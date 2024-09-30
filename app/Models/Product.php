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
}
