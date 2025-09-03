<?php

namespace App\Http\Resources;

use App\Enums\Customer\CustomerStatus;
use App\Enums\Customer\VerificationStatus;
use App\Enums\Media\MediaType;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $role = $this->getRoleNames()->first();

        $data = [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'second_name'=> $this->second_name,
            'third_name' => $this->third_name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'role'       => $role,
        ];

        if ($this->hasRole('customer')) {
            $data['commercial_register'] = optional(
                $this->media?->firstWhere('type', MediaType::COMMERCIAL_REGISTER->value)
            )?->url;

            $data['profile_image'] = optional(
                $this->media?->firstWhere('type', MediaType::USER_PROFILE->value)
            )?->url;

            $data['orders_count']    = (int)($this->order_customers_count ?? 0);
            $data['shipments_count'] = (int)($this->shipments_count ?? 0);

            $data['customer_status'] = $this->status !== null
                ? CustomerStatus::from((int)$this->status)->label()
                : null;
        }

        if ($request->user()?->hasRole('admin')) {
            $data['verification_status'] = $this->is_verified !== null
                ? VerificationStatus::from((int)$this->is_verified)->label()
                : null;
        }

        if (in_array($role, ['employee', 'accountant', 'shipment manager'], true)) {
            [$assigned, $inProcess, $delivered] = $this->computeShipmentWorkload($role);

            $data['workload'] = [
                'assigned_total' => (int)$assigned,
                'in_process'     => (int)$inProcess,
                'delivered'      => (int)$delivered,
            ];
        }

        return $data;
    }

    private function roleToOrderColumn(?string $role): ?string
    {
        return match ($role) {
            'employee'          => 'employee_id',
            'accountant'        => 'accountant_id',
            'shipment manager'  => 'shipping_manager_id',
            default             => null,
        };
    }


    private function computeShipmentWorkload(string $role): array
    {
        $col = $this->roleToOrderColumn($role);
        if (!$col) {
            return [0, 0, 0];
        }

        $rows = Shipment::query()
            ->select('status', DB::raw('COUNT(*) as c'))
            ->whereHas('shipmentOrder', function ($q) use ($col) {
                $q->where($col, $this->id);
            })
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        $inProcess = (int)($rows['in_process'] ?? 0);
        $delivered = (int)($rows['delivered'] ?? 0);
        $pending   = (int)($rows['pending'] ?? 0);

        $assignedTotal = $pending + $inProcess + $delivered;

        return [$assignedTotal, $inProcess, $delivered];
    }
}
