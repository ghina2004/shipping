<?php

namespace App\Services\Contract;

use App\Enums\Contract\ContractStatusEnum;
use App\Enums\Contract\ContractTypeEnum;
use App\Models\Contract;
use App\Models\Shipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ContractService
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

    private function moveToPublic(UploadedFile $file, string $relativeDir, string $baseName): string
    {
        $absDir = $this->ensurePublicDir($relativeDir);
        $ext    = $file->getClientOriginalExtension();
        $name   = $baseName . '.' . $ext;
        $file->move($absDir, $name);
        return $relativeDir . '/' . $name;
    }

    public function bootstrapOnShipmentConfirm(Shipment $shipment): array
    {
        $service = Contract::query()->firstOrNew([
            'shipment_id' => $shipment->id,
            'type'        => ContractTypeEnum::Service->value,
        ]);
        $service->title = 'Service Agreement';

        $servicePdf = Pdf::loadView('contracts.service', ['shipment' => $shipment])->output();

        $dirRel = 'contracts/service/unsigned/' . $shipment->id;
        $dirAbs = $this->ensurePublicDir($dirRel);
        $name   = 'service_' . $shipment->id . '_' . time() . '.pdf';
        $rel    = $dirRel . '/' . $name;
        $abs    = $dirAbs . '/' . $name;

        $this->deletePublicFile($service->unsigned_file_path);
        file_put_contents($abs, $servicePdf);

        $service->unsigned_file_path  = $rel;
        $service->status              = ContractStatusEnum::Final->value;
        $service->visible_to_customer = true;
        $service->save();

        $goods = Contract::query()->firstOrNew([
            'shipment_id' => $shipment->id,
            'type'        => ContractTypeEnum::GoodsDescription->value,
        ]);
        $goods->title = 'Goods Description';

        $goodsPdf = Pdf::loadView('contracts.goods_description', ['shipment' => $shipment])->output();

        $dirRelG = 'contracts/goods_description/' . $shipment->id;
        $dirAbsG = $this->ensurePublicDir($dirRelG);
        $nameG   = 'goods_' . $shipment->id . '_' . time() . '.pdf';
        $relG    = $dirRelG . '/' . $nameG;
        $absG    = $dirAbsG . '/' . $nameG;

        $this->deletePublicFile($goods->unsigned_file_path);
        file_put_contents($absG, $goodsPdf);

        $goods->unsigned_file_path  = $relG;
        $goods->status              = ContractStatusEnum::Final->value;
        $goods->visible_to_customer = true;
        $goods->save();

        return [$service->refresh(), $goods->refresh()];
    }

    public function employeeUploadBillOfLading(Shipment $shipment, ?UploadedFile $file, string $title, int $employeeId): Contract
    {
        $contract = Contract::query()->firstOrNew([
            'shipment_id' => $shipment->id,
            'type'        => ContractTypeEnum::BillOfLading->value,
        ]);

        $contract->title       = $title ?: 'Bill of Lading';
        $contract->uploaded_by = $employeeId;

        if ($file) {
            $this->deletePublicFile($contract->unsigned_file_path);
            $dir   = 'contracts/' . ContractTypeEnum::BillOfLading->value . '/final/' . $shipment->id;
            $base  = 'bol_' . Str::slug($shipment->number ?? (string)$shipment->id) . '_' . time();
            $rel   = $this->moveToPublic($file, $dir, $base);
            $contract->unsigned_file_path = $rel;
        }

        $contract->status = ContractStatusEnum::Final->value;
        $contract->visible_to_customer = true;
        $contract->save();

        return $contract->refresh();
    }

    public function customerUploadSignedService(Shipment $shipment, UploadedFile $signedPdf): Contract
    {
        $contract = Contract::query()->firstOrCreate(
            ['shipment_id' => $shipment->id, 'type' => ContractTypeEnum::Service->value],
            ['title' => 'Service Agreement']
        );

        $this->deletePublicFile($contract->signed_file_path);

        $dir  = 'contracts/' . ContractTypeEnum::Service->value . '/signed/' . $shipment->id;
        $base = 'service_signed_' . Str::slug($shipment->number ?? (string)$shipment->id) . '_' . time();
        $rel  = $this->moveToPublic($signedPdf, $dir, $base);

        $contract->signed_file_path = $rel;
        $contract->signed_at        = now();
        $contract->status           = ContractStatusEnum::Signed->value;
        $contract->save();

        return $contract->refresh();
    }
}
