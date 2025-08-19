<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTrackingLog extends Model
{
    protected $fillable = ['order_id', 'location'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
