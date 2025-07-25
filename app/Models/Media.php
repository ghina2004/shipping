<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
   protected $guarded = [];

    public function mediable():MorphTo
    {
        return $this->morphTo();
    }
}
