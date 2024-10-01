<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class order extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'vendor_id',
        'total',
        'status',
    ];

    public function orderItems()
    {
        return $this->hasMany(order_items::class, 'order_id'); // Référence au modèle order_items
    }

}
