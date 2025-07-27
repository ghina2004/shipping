<?php

namespace App\Services\Shipment;

use App\Enums\shipment\ServiceType;
use App\Enums\shipment\ShippingMethod;
use App\Helper\FileHelper;
use App\Models\Cart;
use App\Models\Shipment;
use App\Models\ShipmentDocument;
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
                throw new \Exception('User does not have a cart.');
            }

            $supplier = null;
            $document = null;

            if (!empty($data['having_supplier']) && $data['having_supplier']) {
                // إنشاء المعمل
                $supplierData = $data['supplier'] ?? [];
                $supplierData['user_id'] = $user->id;
                $supplier = Supplier::create($supplierData);
            }
            $dataWithoutSupplier = collect($data)->except(['supplier', 'sup_invoice'])->toArray();
            // إنشاء الشحنة
            $shipment = Shipment::create(array_merge($dataWithoutSupplier, [
                'cart_id' => $cart->id,
                'supplier_id' => $supplier?->id,
                'number' => Str::upper(Str::random(5)),
                'service_type' => ServiceType::from($data['service_type']),
                'shipping_method' => ShippingMethod::from($data['shipping_method']),
            ]));

            // إذا يوجد ملف، خزنه
            if (!empty($data['sup_invoice'])) {
                $filePath = FileHelper::upload($data['sup_invoice'], 'shipment_documents');

                $document = ShipmentDocument::create([
                    'shipment_id' => $shipment->id,
                    'type' => 'sup_invoice',
                    'file_path' => $filePath,
                    'uploaded_by' => $user->id,
                    'visible_to_customer' => true,
                ]);
            }

            return [
                'shipment' => $shipment,
                'supplier' => $supplier,
                'document' => $document,
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

}
