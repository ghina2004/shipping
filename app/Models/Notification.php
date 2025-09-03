<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable =[
        'user_id', 'shipment_id', 'title' ,'body' ,'read'
    ];

    public function shipmentNotification()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function userNotification()
    {
        return $this->belongsTo(User::class);
    }
}
