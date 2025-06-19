<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{   protected  $fillable=[ 'selected_by' ,'name' , 'address'
    ,'contact_email' ,'contact_phone'
   ];


    public function supplierShipment()
    {
        return $this->hasOne(Shipment::class);
    }

}
