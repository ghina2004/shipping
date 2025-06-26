<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentQuestion extends Model
{
    protected $fillable = ['question_ar', 'question_en', 'type'];

    public function getQuestionAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->question_ar : $this->question_en;
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_shipment_question');
    }

    public function questionOption()
    {
        return $this->hasMany(QuestionOptions::Class);
    }

    public function shipmentSupplier()
    {
        return $this->hasMany(Supplier::class);
    }
    public function ShipmentAnswers()
    {
        return $this->hasMany(ShipmentAnswer::class);
    }
}
