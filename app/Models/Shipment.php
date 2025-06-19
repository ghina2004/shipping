<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{   protected $fillable =[  'cart_id', 'number', 'shipping_date', 'service_type',
    'origin_country', 'destination_country', 'shipping_method', 'cargo_weight',
    'cantainers_size', 'containers_numbers', 'notes' , 'status','Supplier_id','category_id'
];

    public function shipmentCart()
    {
        return $this->belongsTo(Cart::class);
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

    public function shipmentRoute(){
        return $this->hasOne(ShipmentRoute::class);
    }

    public function shipmentNotifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function answersShipment()
    {
        return $this->hasMany(ShipmentAnswer::class);
    }

    public function ShipmentDocuments()
    {
        return $this->hasMany(ShipmentDocument::class);
    }

    public function nvoiceShipment(){
        return $this->hasOne(ShipmentInvoice::class);
    }

    public function InvoiceFiles()
    {
        return $this->hasMany(InvoiceFile::class);
    }


}
