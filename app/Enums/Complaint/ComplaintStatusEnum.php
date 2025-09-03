<?php

namespace App\Enums\Complaint;

enum ComplaintStatusEnum: string
{
    case Open     = 'open';
    case Replied  = 'replied';
    case Resolved = 'resolved';

    public function label(): string
    {
        return match ($this) {
            self::Open     => __('enum.complaint_status.open'),
            self::Replied  => __('enum.complaint_status.replied'),
            self::Resolved => __('enum.complaint_status.resolved'),
        };
    }
}
