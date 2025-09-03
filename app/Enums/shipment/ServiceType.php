<?php

namespace App\Enums\shipment;

enum ServiceType : string
{
    case IMPORT = 'import';
    case EXPORT = 'export';
}
