<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected  $fillable =['customer_id', 'employee_id', 'shipment_id',
        'service_rate', 'employee_rate' , 'comment'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

}
