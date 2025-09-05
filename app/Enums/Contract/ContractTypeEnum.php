<?php

namespace App\Enums\Contract;

enum ContractTypeEnum: string
{
    case Service           = 'service';
    case GoodsDescription  = 'goods_description';
    case Other      = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Service          => __('enum.contract_type.service'),
            self::GoodsDescription => __('enum.contract_type.goods_description'),
            self::Other     => __('enum.contract_type.other'),
        };
    }
}
