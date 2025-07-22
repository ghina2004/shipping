<?php

namespace App\Services\Shipment;

use App\Enums\shipment\ServiceType;
use App\Enums\shipment\ShippingMethod;
use App\Models\Cart;
use App\Models\Shipment;
use App\Models\Supplier;
use App\Models\User;
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


    public function createWithOptionalSupplier(array $data, User $user): array
    {
        return DB::transaction(function () use ($data, $user) {

            $cart = $user->cart;
            if (!$cart) {
                $cart = Cart::create(['customer_id' => $user->id]);
            }

            if (!empty($data['having_supplier']) && $data['having_supplier']) {
                $supplierData = $data['supplier'] ?? [];
                $supplierData['user_id'] = $user->id;

                $supplier = Supplier::create($supplierData);
            }

            $shipment = Shipment::create(array_merge($data, [
                'cart_id' => $user-> $cart->id,
                'supplier_id' => $supplier?->id,
                'number' => Str::upper(Str::random(5)),
                'service_type' => ServiceType::from($data['service_type']),
                'shipping_method' => ShippingMethod::from($data['shipping_method']),
            ]));

            return [
                'shipment' => $shipment,
                'supplier' => $supplier ?? null,
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


    public function confirmShipment(Shipment $shipment)
    {
        if (! $shipment->is_information_complete) {
            return [
                'success' => false,
                'message' => 'Shipment cannot be confirmed until its information is complete.',
            ];
        }

        $shipment->update([
            'is_confirm' => true,
        ]);

        return [
            'success' => true,
            'message' => 'shipment confirmed successfully',
        ];
    }

}
