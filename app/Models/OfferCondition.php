<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferCondition extends Model
{

    protected $fillable =[
      'offer_id', 'condition_type' ,'condition_value'
    ];


    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function userOffer()
    {
        return $this->hasMany(UserOffer::class);
    }

}
