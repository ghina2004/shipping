<?php

namespace App\Console\Commands;

use App\Enums\Customer\CustomerSegments;
use App\Enums\Customer\CustomerStatus;
use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class ChangeCustomersStatus extends Command
{
    protected $signature = 'customers:change-status';
    protected $description = 'Change customers status (new → old) based on rules';

    public function handle(): int
    {
        $thresholdOrders = CustomerSegments::ORDER->value;
        $thresholdDays   = CustomerSegments::DAYS->value;
        $oldCustomer = CustomerStatus::OLD->value;
        $newCustomer = CustomerStatus::NEW->value;

        $cutoffDate = Carbon::now()->subDays($thresholdDays);

        $affected = User::role('customer')
            ->where('status', $oldCustomer)
            ->where(function ($q) use ($thresholdOrders, $cutoffDate) {
                $q->whereHas('orders', function ($query) use ($thresholdOrders) {
                    $query->havingRaw('COUNT(*) >= ?', [$thresholdOrders]);
                })
                    ->orWhere('created_at', '<=', $cutoffDate);
            })
            ->update(['status' => $newCustomer]);

        $this->info("Customers updated to old: {$affected}");

        return self::SUCCESS;
    }
}
