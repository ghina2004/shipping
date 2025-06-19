<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected  $fillable =['customer_id', 'employee_id', 'shipment_id',
        'service_rate', 'employee_rate' , 'comment'
    ];

    public function shipmentRate()
    {
        return $this->belongsTo(Shipment::class);
    }
    public function customerRate()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function employeeRate()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

}
