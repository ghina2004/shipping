<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = ['name_ar', 'name_en'];

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function categoryShipments()
    {
        return $this->hasMany(Shipment::class);

    }

    public function ShipmentQuestions()
    {
        return $this->belongsToMany(ShipmentQuestion::class, 'category_shipment_question');

    }

}
