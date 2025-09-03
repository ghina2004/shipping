<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
  protected $fillable =[
    'coupon_code', 'title', 'description',
    'is_conditional', 'discount_type', 'discount_value',
   'start_date', 'end_date'
    ];


    public function condition()
    {
        return $this->hasOne(OfferCondition::class);
    }

}
