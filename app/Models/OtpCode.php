<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtpCode extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'otp_codes';

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
        'is_used',
    ];

    protected $dates = [
        'expires_at',
        'deleted_at',
    ];

    public function employee()
    {
        return $this->belongsTo(employee::class, 'user_id');
    }

}
