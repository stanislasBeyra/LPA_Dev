<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'mobile',
        'avatar',
        'status',
        'net_salary',
        'role',
        'agencescode',
        'password',
    ];

    const ROLE_ADMIN = 1;
    const ROLE_VENDOR = 2;
    const ROLE_CLIENT = 3;
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function vendor()
    {
        return $this->hasOne(vendor::class, 'user_id');  // Assurez-vous que 'user_id' est la clé étrangère dans la table 'vendors'
    }

    public function role()
    {
        return $this->belongsTo(roles::class, 'role'); // Si chaque utilisateur a un seul rôle
    }


    public function isVendor(): bool
    {
        return $this->role === self::ROLE_VENDOR;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
