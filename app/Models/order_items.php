<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class order_items extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'total',
    ];

    // Relations
    public function order()
    {
        return $this->belongsTo(order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
