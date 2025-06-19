<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentAnswer extends Model
{
    protected $fillable = [ 'shipment_id', 'user_id','shipment_question_id', 'answer'
        ];

    public function shipmentQuestion()
    {
        return $this->belongsTo(ShipmentQuestion::class);
    }
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
    public function UserShipmentAnswer()
    {
        return $this->belongsTo(User::class);
    }


}
