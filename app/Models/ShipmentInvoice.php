<?php

namespace App\Models;

use App\Enums\Invoice\InvoiceType;
use Illuminate\Database\Eloquent\Model;

class ShipmentInvoice extends Model
{
    protected $fillable =['notes','final_amount', 'company_profit', 'service_fee',
    'customs_fee','initial_amount' , 'invoice_type' ,'invoice_number','shipment_id'
    ];
    protected $casts = [
        'invoice_type' => InvoiceType::class,
    ];
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
    public function ShipmentPayment(){
        return $this->hasOne(ShipmentPayment::class);
    }


}
