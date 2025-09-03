<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTrackingLog extends Model
{
    protected $fillable = ['shipment_id', 'location'];

    public function shipmentLog()
    {
        return $this->belongsTo(Shipment::class);
    }
}
