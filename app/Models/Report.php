<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title', 'report_for_id' , 'report_type',
        'report_period' , 'url'
    ];

    public function reportFor()
    {
        return $this->belongsTo(User::class, 'report_for_id');
    }





}
