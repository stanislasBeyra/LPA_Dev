<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'vendorname',
        'contactpersonname',
        'businessregno',
        'taxidnumber',
        'businesscategory',
        'businessaddress',
        'bankname1',
        'bankaccount1',
        'bankname2',
        'bankaccount2',
        'accountholdername',
        'businesscertificate',
        'taxcertificate',
        'passportorID',
    ];

    public function user()
{
    return $this->belongsTo(User::class);  // Cela suppose que le modèle 'Vendor' a une colonne 'user_id'
}

}
