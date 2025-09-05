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
        // عقد الخدمة
        $service = Contract::query()->firstOrNew([
            'shipment_id' => $shipment->id,
            'type'        => ContractTypeEnum::Service->value,
        ]);
        $service->title = 'Service Agreement';

        $servicePdf = Pdf::loadView('contracts.service_contract', ['shipment' => $shipment])->output();

        $relDir = 'contracts/service/unsigned/' . $shipment->id;
        $absDir = $this->ensurePublicDir($relDir);
        $name   = 'service_' . $shipment->id . '_' . time() . '.pdf';
        $rel    = $relDir . '/' . $name;
        $abs    = $absDir . '/' . $name;

        $this->deletePublicFile($service->unsigned_file_path);
        file_put_contents($abs, $servicePdf);

        $service->unsigned_file_path        = $rel;
        $service->status                    = ContractStatusEnum::PendingSignature->value;
        $service->show_unsigned_to_customer = true;
        $service->show_signed_to_customer   = false;
        $service->save();

        $goods = Contract::query()->firstOrNew([
            'shipment_id' => $shipment->id,
            'type'        => ContractTypeEnum::GoodsDescription->value,
        ]);
        $goods->title = 'Goods Description';

        $goodsPdf = Pdf::loadView('contracts.goods_description', ['shipment' => $shipment])->output();

        $relDirG = 'contracts/goods_description/' . $shipment->id;
        $absDirG = $this->ensurePublicDir($relDirG);
        $nameG   = 'goods_' . $shipment->id . '_' . time() . '.pdf';
        $relG    = $relDirG . '/' . $nameG;
        $absG    = $absDirG . '/' . $nameG;

        $this->deletePublicFile($goods->unsigned_file_path);
        file_put_contents($absG, $goodsPdf);

        $goods->unsigned_file_path        = $relG;
        $goods->status                    = ContractStatusEnum::Final->value;
        $goods->show_unsigned_to_customer = true;
        $goods->show_signed_to_customer   = false;
        $goods->save();

        return [$service->refresh(), $goods->refresh()];
    }


    public function employeeUploadGenericContract(
        Shipment $shipment,
        UploadedFile $file,
        string $title,
        int $employeeId,
        bool $visibleToCustomer = true
    ): Contract {
        $type = ContractTypeEnum::Other->value;

        $contract = new Contract();
        $contract->shipment_id               = $shipment->id;
        $contract->type                      = $type;
        $contract->title                     = $title;
        $contract->uploaded_by               = $employeeId;
        $contract->status                    = ContractStatusEnum::Final->value;
        $contract->show_unsigned_to_customer = $visibleToCustomer ? 1 : 0;
        $contract->show_signed_to_customer   = 0;

        $dir  = 'contracts/' . $type . '/final/' . $shipment->id;
        $base = Str::slug($title) . '_' . time();
        $rel  = $this->moveToPublic($file, $dir, $base);

        $contract->unsigned_file_path = $rel;
        $contract->save();

        return $contract->fresh();
    }


    public function customerUploadSignedService(Shipment $shipment, UploadedFile $signedPdf): Contract
    {
        $contract = Contract::query()->firstOrCreate(
            ['shipment_id' => $shipment->id, 'type' => ContractTypeEnum::Service->value],
            ['title' => 'Service Agreement']
        );

        // حذف الموقّع القديم إن وجد
        $this->deletePublicFile($contract->signed_file_path);

        $dir  = 'contracts/' . ContractTypeEnum::Service->value . '/signed/' . $shipment->id;
        $base = 'service_signed_' . Str::slug($shipment->number ?? (string)$shipment->id) . '_' . time();
        $rel  = $this->moveToPublic($signedPdf, $dir, $base);

        $contract->signed_file_path          = $rel;
        $contract->signed_at                 = now();
        $contract->status                    = ContractStatusEnum::Signed->value;
        // إظهار الموقّع وإخفاء غير الموقّع للعميل
        $contract->show_unsigned_to_customer = false;
        $contract->show_signed_to_customer   = true;
        $contract->save();

        return $contract->refresh();
    }

    /* ===== موظف/أدمن: إعادة عقد الخدمة إلى مرحلة التوقيع ===== */

    public function resetServiceSignature(Shipment $shipment, int $byUserId): Contract
    {
        $contract = Contract::query()
            ->where('shipment_id', $shipment->id)
            ->where('type', ContractTypeEnum::Service->value)
            ->firstOrFail();

        // حذف الموقّع
        $this->deletePublicFile($contract->signed_file_path);

        $contract->update([
            'signed_file_path'          => null,
            'signed_at'                 => null,
            'status'                    => ContractStatusEnum::PendingSignature->value,
            'show_unsigned_to_customer' => true,
            'show_signed_to_customer'   => false,
        ]);

        return $contract->refresh();
    }


    public function deleteUploadedContract(Contract $contract, int $byUserId): bool
    {
        if (!empty($contract->unsigned_file_path)) {
            $this->deletePublicFile($contract->unsigned_file_path);
        }

        return (bool) $contract->update([
            'unsigned_file_path'        => null,
            'show_unsigned_to_customer' => false,
        ]);
    }
}
