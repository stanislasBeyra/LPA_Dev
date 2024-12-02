<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    // Nom de la table associée (si différent du nom par défaut)
   // protected $table = 'banners';

    // Colonnes autorisées pour l'attribution de masse
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'is_active',
    ];

    // Gestion des colonnes de dates supplémentaires
    protected $dates = ['deleted_at'];
}
