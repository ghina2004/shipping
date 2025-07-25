<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $guarded = [];
    public function OrderInvoice(){
        return $this->belongsTo(OrderInvoice::class);
    }
}
