<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    source /home/softsol4/nodevenv/Dashbord_lpa/10/bin/activate && cd /home/softsol4/Dashbord_lpa
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
