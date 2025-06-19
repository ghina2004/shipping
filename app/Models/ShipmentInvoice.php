<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentInvoice extends Model
{
    protected $fillable =[   'notes','final_amount', 'company_profit', 'service_fee',
    'customs_fee','initial_amount' , 'invoice_type' ,'invoice_number','shipment_id'
        ];

    public function shipmentInvoice()
    {
        return $this->belongsTo(Shipment::class);
    }
    public function ShipmentPayment(){
        return $this->hasOne(ShipmentPayment::class);
    }


}
