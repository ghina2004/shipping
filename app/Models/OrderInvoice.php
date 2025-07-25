<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    protected $guarded = [];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderPayment()
    {
        return $this->hasOne(OrderPayment::class, 'order_invoice_id');
    }
}
