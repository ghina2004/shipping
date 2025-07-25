<?php

namespace App\Models;

use App\Enums\shipment\ServiceType;
use App\Enums\shipment\ShippingMethod;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'service_type' => ServiceType::class,
        'shipping_method' => ShippingMethod::class,
    ];

    public function shipmentCart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function shipmentOrder()
    {
        return $this->belongsTo(Order::class);
    }

    public function shipmentCategory()
    {
        return $this->belongsTo(Category::class);
    }

    public function shipmentRatings()
    {
        return $this->hasMany(Rating::class);
    }

    public function shipmentSupplier()
    {
        return $this->belongsTo(Supplier::class);
    }



    public function shipmentNotifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function answersShipment()
    {
        return $this->hasMany(ShipmentAnswer::class);
    }

    public function shipmentDocuments()
    {
        return $this->hasMany(ShipmentDocument::class);
    }

    public function nvoiceShipment(){
        return $this->hasOne(ShipmentInvoice::class);
    }

    public function invoice()
    {
        return $this->hasMany(ShipmentInvoice::class);
    }


}
