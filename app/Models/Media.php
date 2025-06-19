<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
      'url', 'type', 'imageable'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
