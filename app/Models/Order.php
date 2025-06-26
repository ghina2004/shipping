<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function customerOrder()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function employeeOrder()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function shippingManagerOrder()
    {
        return $this->belongsTo(User::class, 'shipping_manager_id');
    }

    public function accountantOrder()
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    public function orderConversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function orderShipments()
    {
        return $this->hasMany(Shipment::class);
    }


}
