<?php

namespace App\Models;

use App\Enums\Complaint\ComplaintStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'customer_id','subject','message',
        'status','admin_reply','replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }

    public function statusEnum(): ComplaintStatusEnum {
        return ComplaintStatusEnum::from($this->status);
    }
}
