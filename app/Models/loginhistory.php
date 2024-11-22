<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class loginhistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'loginhistories'; 

    protected $fillable = [
        'user_id',
        'login_time',
        'ip_address',
        'device',
    ];

    protected $casts = [
        'login_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
