<?php

namespace App\Enums\Customer;

enum VerificationStatus: int
{
    case NOT_VERIFIED = 0;
    case VERIFIED = 1;

    public function label(): string
    {
        return match (app()->getLocale()) {
            'ar' => match ($this) {
                self::NOT_VERIFIED => 'غير مقبول',
                self::VERIFIED     => 'مقبول',
            },
            default => match ($this) {
                self::NOT_VERIFIED => 'Not Verified',
                self::VERIFIED     => 'Verified',
            }
        };
    }
}
