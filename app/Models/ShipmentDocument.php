<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentDocument extends Model
{
    protected $fillable =[
        'shipment_id','type' ,'file_path', 'uploaded_by','visible_to_customer'
    ];
    public function shipmentDocument()
    {
        return $this->belongsTo(Shipment::class);
    }

}
