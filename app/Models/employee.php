<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class employee extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'mobile',
        'avatar',
        'status',
        'net_salary',
        'agencescode',
        'email_verified_at',
        'password',
        'role',
    ];

    // Attributs à traiter comme des dates
    protected $dates = ['email_verified_at', 'deleted_at'];

    // Relation avec le modèle Role
    public function role()
    {
        return $this->belongsTo(roles::class, 'role');
    }
}
