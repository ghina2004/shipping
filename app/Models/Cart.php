<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable =[
      'customer_id', 'employee_id', 'shipping_manager_id',
      'cart_number'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function shippingManager()
    {
        return $this->belongsTo(User::class, 'shipping_manager_id');
    }
    public function cartConversations()
    {
        return $this->hasMany(Conversation::class);
    }

}
