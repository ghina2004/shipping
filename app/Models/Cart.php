<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];

    public function customerCart()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function employeeCart()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function shippingManagerCart()
    {
        return $this->belongsTo(User::class, 'shipping_manager_id');
    }

    public function accountantCart()
    {
        return $this->belongsTo(User::class, 'accountant_id');
    }

    public function cartOrders()
    {
        return $this->hasMany(Shipment::class);
    }

    public function cartConversations()
    {
        return $this->hasMany(Conversation::class);
    }




}
