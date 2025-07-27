<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdertRoute extends Model
{
    protected $fillable =[
        'order_id', 'tracking_number' ,'tracking_link','status'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
