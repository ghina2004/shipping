<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentRoute extends Model
{
    protected $fillable =[
        'order_id', 'tracking_number' ,'tracking_link'
    ];

    public function order(){
        return $this->belongsTo(Shipment::class);
    }
}
