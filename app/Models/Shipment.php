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
        return $this->belongsTo(Order::class,'order_id');
    }

    public function shipmentCategory()
    {
        return $this->belongsTo(Category::class);
    }


    public function shipmentSupplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id' ,'id');
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

    public function invoiceShipment(){
        return $this->hasOne(ShipmentInvoice::class);
    }

    public function invoice()
    {
        return $this->hasOne(ShipmentInvoice::class);
    }
    public function originalCompany()
    {
        return $this->belongsTo(OriginalShippingCompany::class, 'original_company_id');
    }

    public function shipmentRoute(){
        return $this->hasOne(ShipmentRoute::class);
    }
    public function trackingLogs()
    {
        return $this->hasMany(ShipmentTrackingLog::class);
    }

    public function contracts() { return $this->hasMany(\App\Models\Contract::class); }

}
