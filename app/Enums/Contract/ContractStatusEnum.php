<?php

namespace App\Enums\Contract;

enum ContractStatusEnum: string
{
    case PendingSignature = 'pending_signature';
    case Signed           = 'signed';
    case Final            = 'final';

    public function label(): string
    {
        return match ($this) {
            self::PendingSignature => __('enum.contract_status.pending_signature'),
            self::Signed           => __('enum.contract_status.signed'),
            self::Final            => __('enum.contract_status.final'),
        };
    }
}
