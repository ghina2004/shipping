<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOffer extends Model
{
    protected $fillable =[
    'offer_condition_id', 'user_id'
     ];


     public function offerCondition()
     {
        return $this->belongsTo(OfferCondition::class);
     }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
