<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentPayment extends Model
{
    protected $fillable =[
        'shipment_invoice_id', 'paid_amount' ,'due_amount', 'status',
        'paid_at', 'due_date'
    ];

    public function ShipmentInvoice(){
        return $this->belongsTo(ShipmentInvoice::class);
    }






}
