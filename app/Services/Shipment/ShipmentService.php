<?php

namespace App\Services\Shipment;

use App\Enums\shipment\ServiceType;
use App\Enums\shipment\ShippingMethod;
use App\Models\Cart;
use App\Models\Shipment;
use App\Services\Order\OrderService;
use App\Services\Category\CategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShipmentService
{
    public function __construct(
        protected ShipmentUpdateStrategy $updateStrategy, protected OrderService $orderService,
        protected CategoryService $categoryService)
    {}


    public function createShipment(array $data, $user)
    {
        return DB::transaction(function () use ($data, $user) {
            $shipment = Shipment::create(array_merge($data, [
                'cart_id' => $user->cart?->id,
                'number' => Str::upper(Str::random(5)),
                'service_type' => ServiceType::from($data['service_type']),
                'shipping_method' => ShippingMethod::from($data['shipping_method'])
            ]));
            return [
                'shipment' => $shipment,
            ];
        });

    }


    public function show($shipmentId)
    {
        return Shipment::query()->findOrFail($shipmentId);
    }

    public function update(array $data, $shipmentId): Shipment
    {
        return $this->updateStrategy->handle(auth()->user(), $data, $shipmentId);
    }


    public function delete(Shipment $shipment)
    {
        $shipment->delete();
    }


    public function confirm()
    {

    }

}
