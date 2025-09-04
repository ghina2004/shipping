<?php

namespace App\Services\Contract;

use App\Enums\Contract\ContractTypeEnum;
use App\Models\Contract;
use App\Models\Shipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ContractDownloadService
{
    private function ensurePublicDir(string $relativeDir): string
    {
        $abs = public_path($relativeDir);
        if (!is_dir($abs)) mkdir($abs, 0775, true);
        return $abs;
    }

    private function deletePublicFile(?string $relativePath): void
    {
        if (!$relativePath) return;
        $abs = public_path($relativePath);
        if (is_file($abs)) @unlink($abs);
    }

    public function downloadServiceContract(Shipment $shipment)
    {
        $contract = Contract::firstOrCreate(
            ['shipment_id' => $shipment->id, 'type' => ContractTypeEnum::Service->value],
            ['title' => 'Service Contract']
        );

        // أنشئ PDF جديد دائمًا (ونستبدل السابق)
        $pdf    = Pdf::loadView('contracts.service_contract', ['shipment' => $shipment]);
        $dirRel = "contracts/{$shipment->id}";
        $dirAbs = $this->ensurePublicDir($dirRel);
        $name   = 'service_contract_' . Str::slug($shipment->number) . '.pdf';
        $rel    = "{$dirRel}/{$name}";
        $abs    = "{$dirAbs}/{$name}";

        // احذف القديم إن وجد
        $this->deletePublicFile($contract->unsigned_file_path);

        file_put_contents($abs, $pdf->output());
        $contract->update(['unsigned_file_path' => $rel]);

        return response()->download($abs, $name);
    }

    public function downloadGoodsDescription(Shipment $shipment)
    {
        $contract = Contract::firstOrCreate(
            ['shipment_id' => $shipment->id, 'type' => ContractTypeEnum::GoodsDescription->value],
            ['title' => 'Goods Description']
        );

        $pdf    = Pdf::loadView('contracts.goods_description', ['shipment' => $shipment]);
        $dirRel = "contracts/{$shipment->id}";
        $dirAbs = $this->ensurePublicDir($dirRel);
        $name   = 'goods_description_' . Str::slug($shipment->number) . '.pdf';
        $rel    = "{$dirRel}/{$name}";
        $abs    = "{$dirAbs}/{$name}";

        $this->deletePublicFile($contract->unsigned_file_path);

        file_put_contents($abs, $pdf->output());
        $contract->update(['unsigned_file_path' => $rel]);

        return response()->download($abs, $name);
    }

    public function downloadBillOfLadingByShipment(Shipment $shipment)
    {
        $contract = Contract::query()
            ->where('shipment_id', $shipment->id)
            ->where('type', ContractTypeEnum::BillOfLading->value)
            ->firstOrFail();

        $abs = public_path($contract->unsigned_file_path);
        abort_unless(is_file($abs), 404);

        $name = basename($abs) ?: 'bill_of_lading.pdf';
        return response()->download($abs, $name);
    }

    public function downloadSignedServiceByShipment(Shipment $shipment)
    {
        $contract = Contract::query()
            ->where('shipment_id', $shipment->id)
            ->where('type', ContractTypeEnum::Service->value)
            ->firstOrFail();

        $abs = public_path($contract->signed_file_path);
        abort_unless($contract->signed_file_path && is_file($abs), 404);

        $name = basename($abs) ?: 'service_contract_signed.pdf';
        return response()->download($abs, $name);
    }
}
