<?php

namespace App\Services\Shipment;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Arr;

class ShipmentUpdateStrategy
{
    public function handle(User $user, array $data, $shipmentId): Shipment
    {
        $shipment = Shipment::query()->findOrFail($shipmentId);

        $filtered = $this->filterRestrictedFields($user, $data);

        $shipment->update(array_filter($filtered, fn($v) => !is_null($v)));

        return $shipment;
    }

    protected function filterRestrictedFields(User $user, array $data): array
    {
        if ($user->hasRole('employee')) {
            return Arr::except($data, ['customer_notes']);
        }

        if ($user->hasRole('customer')) {
            return Arr::except($data, ['employee_notes']);
        }

        if ($user->hasRole('shipment_manager')) {
            return Arr::only($data, ['shipping_date', 'shipping_method', 'origin_country','cargo_weight','containers_size','containers_numbers']);
        }

        return $data;
    }
}


