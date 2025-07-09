<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];

    public function customerCart()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

}
