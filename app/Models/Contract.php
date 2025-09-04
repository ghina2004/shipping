<?php

namespace App\Models;

use App\Enums\Contract\ContractStatusEnum;
use App\Enums\Contract\ContractTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $guarded = [];

    protected $casts = [
        'signed_at' => 'datetime',
        'visible_to_customer' => 'boolean',
    ];

    public function shipment()   { return $this->belongsTo(Shipment::class); }
    public function uploader()   { return $this->belongsTo(User::class, 'uploaded_by'); }

    public function typeEnum(): ContractTypeEnum
    {
        return ContractTypeEnum::from($this->type);
    }

    public function statusEnum(): ContractStatusEnum
    {
        return ContractStatusEnum::from($this->status);
    }
}
