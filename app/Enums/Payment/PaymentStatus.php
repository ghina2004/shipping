<?php

namespace App\Enums\Payment;

enum PaymentStatus : string
{
    case COMPLETE = 'complete';
    case PARTIAL = 'partial';
    case PENDING = 'pending';
}
