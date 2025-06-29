<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOptions extends Model
{
    protected $guarded = [];
    public function shipmentQuestion()
    {
        return $this->belongsTo(ShipmentQuestion::class);
    }

}
