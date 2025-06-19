<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable =[
        'type'
    ];

    public function categoryShipments()
    {
        return $this->hasMany(Shipment::class);

    }

    public function ShipmentQuestions()
    {
        return $this->hasMany(ShipmentQuestion::class);

    }
}
