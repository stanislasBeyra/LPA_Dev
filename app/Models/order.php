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
        'total',
        'status',
        'ordercode',
    ];

    public function orderItems()
    {
        return $this->hasMany(order_items::class, 'order_id'); // Référence au modèle order_items
    }

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    public function users()
{
    return $this->belongsTo(User::class); // Assurez-vous que cela correspond à la relation entre Order et User
}
    

    public function employee(){
        return $this->belongsTo(employee::class, 'user_id');
    }

}
