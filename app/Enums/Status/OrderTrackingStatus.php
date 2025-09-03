<?php

namespace App\Enums\Status;

enum OrderTrackingStatus: int
{
    case InProgress = 0;
    case InTransit = 1;
    case Delivered = 2;
}
