<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentQuestion extends Model
{
    protected $fillable =[
        'category_id', 'question', 'type'
    ];

    public function shipmentQuestionCategory()
    {
        return $this->belongsTo(Category::class);
    }

//    public function shipmentSupplier()
//    {
//        return $this->hasMany(Supplier::class);
//    }

    public function ShipmentAnswers()
    {
        return $this->hasMany(ShipmentAnswer::class);
    }
}
