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
        'national_id',
        'firstname',
        'lastname',
        'username',
        'middle_name',
        'email',
        'mobile',
        'mobile2',
        'avatar',
        'status',
        'net_salary',
        'ministry_agency',
        'agencescode',
        'fcm_token',
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
    
    public function agences(){
        return $this->belongsTo(agence::class, 'agencescode');
    }

    public function agence(){
        return $this->belongsTo(agence::class, 'agencescode');
    }

}
