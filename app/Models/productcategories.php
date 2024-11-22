<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class productcategories extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'categories_name',
        'categories_description',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'categorie_id');
    }
}
