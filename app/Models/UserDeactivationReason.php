<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDeactivationReason extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_deactivation_reasons';

    protected $fillable = [
        'user_id',  
        'admin_id',
        'reason'    
    ];
}
