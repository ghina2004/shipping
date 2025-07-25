<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{   protected  $fillable=[   'user_id' ,'name' , 'address'
    ,'contact_email' ,'contact_phone'
   ];

    public function userSupplier()
    {
        return $this->belongsTo(User::class);
    }


    public function supplierShipment()
    {
        return $this->hasOne(Shipment::class);
    }

}
