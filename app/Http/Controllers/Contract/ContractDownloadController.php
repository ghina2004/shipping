<?php

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Services\Contract\ContractDownloadService;

class ContractDownloadController extends Controller
{
    public function __construct(private ContractDownloadService $service) {}

    public function downloadService(Shipment $shipment)
    {
        return $this->service->downloadServiceContract($shipment);
    }

    public function downloadGoods(Shipment $shipment)
    {
        return $this->service->downloadGoodsDescription($shipment);
    }

    public function downloadBOLByShipment(Shipment $shipment)
    {
        return $this->service->downloadBillOfLadingByShipment($shipment);
    }

    public function downloadSignedByShipment(Shipment $shipment)
    {
        return $this->service->downloadSignedServiceByShipment($shipment);
    }
}
