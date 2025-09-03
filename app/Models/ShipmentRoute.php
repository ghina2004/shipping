<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentRoute extends Model
{
    protected $fillable =[
        'shipment_id' ,'tracking_number','tracking_link'
    ];

    public function shipmentTracking(){
        return $this->belongsTo(Shipment::class);
    }
}
