<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginalShippingCompany extends Model
{
    protected $fillable = [
        'name', 'contact_email', 'contact_phone', 'address'
    ];

    public function originalShipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
