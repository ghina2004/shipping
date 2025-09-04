<?php

namespace App\Services\Report;


use App\Models\Complaint;
use App\Models\OrderInvoice;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReportService
{

    public function admin(array $filters): array
    {
        [$from, $to] = $this->range($filters);

        return [
            'meta'               => $this->periodMeta($filters, $from, $to),
            'profit_summary'     => $this->profitSummary($from, $to),
            'costs_summary'      => $this->costsSummary($from, $to),
            'shipments_by_status'=> $this->shipmentStatusBreakdown($from, $to),
            'carriers_breakdown' => $this->carriersBreakdown($from, $to),
            'avg_time_to_deliver_days' => $this->timeToDeliverAvg($from, $to),
            'payments_breakdown' => $this->paymentStatusBreakdown($from, $to),
            'complaints_summary'         => $this->complaintsSummary($from, $to),
        ];
    }


    public function shippingManager(array $filters): array
    {
        [$from, $to] = $this->range($filters);

        return [
            'meta'               => $this->periodMeta($filters, $from, $to),
            'shipping_method_breakdown' => $this->shippingMethodBreakdown($from, $to, $filters['shipping_manager_id'] ?? null),
            'shipment_status_breakdown' => $this->shipmentStatusBreakdown($from, $to, $filters['shipping_manager_id'] ?? null),
            'carriers_breakdown'        => $this->carriersBreakdown($from, $to, $filters['shipping_manager_id'] ?? null),
            'avg_time_to_deliver_days'  => $this->timeToDeliverAvg($from, $to, $filters['shipping_manager_id'] ?? null),
        ];
    }

    private function range(array $f): array
    {
        $period = strtolower(trim($f['period'] ?? 'yearly'));
        $year   = (int) ($f['year'] ?? now()->year);

        switch ($period) {
            case 'weekly': {
                $week = (int) ($f['week'] ?? now()->isoWeek);
                $from = Carbon::now()->setISODate($year, $week)->startOfWeek(Carbon::MONDAY);
                $to   = Carbon::now()->setISODate($year, $week)->endOfWeek(Carbon::SUNDAY)->endOfDay();
                break;
            }

            case 'monthly': {
                $month = (int) ($f['month'] ?? now()->month);
                $month = max(1, min(12, $month));

                $from = Carbon::createFromDate($year, $month, 1)->startOfDay();
                $to   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
                break;
            }

            default: { // yearly
                $from = Carbon::createFromDate($year, 1, 1)->startOfDay();
                $to   = Carbon::createFromDate($year, 12, 31)->endOfDay();
                break;
            }
        }

        return [$from, $to];
    }

    private function periodMeta(array $f, Carbon $from, Carbon $to): array
    {
        $meta = [
            'period' => $f['period'],
            'year'   => (int)$f['year'],
            'from'   => $from->toDateString(),
            'to'     => $to->toDateString(),
        ];

        if ($f['period'] === 'monthly') {
            $meta['month'] = (int)$f['month'];
        }
        if ($f['period'] === 'weekly') {
            $meta['week'] = (int)$f['week'];
        }

        return $meta;
    }



    private function shippingMethodBreakdown(Carbon $from, Carbon $to, ?int $shippingManagerId = null): array
    {
        $q = Shipment::query()
            ->whereBetween('created_at', [$from, $to]);

        if ($shippingManagerId) {
            $q->whereHas('shipmentOrder', function (Builder $q) use ($shippingManagerId) {
                $q->where('shipping_manager_id', $shippingManagerId);
            });
        }

        return $q->select('shipping_method', DB::raw('COUNT(*) as count'))
            ->groupBy('shipping_method')
            ->orderBy('count','desc')
            ->pluck('count','shipping_method')
            ->toArray();
    }
    private function paymentStatusBreakdown(Carbon $from, Carbon $to): array
    {
        return DB::table('orders')
            ->whereBetween('created_at', [$from, $to])
            ->select('payment_status', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_status')
            ->orderBy('count', 'desc')
            ->pluck('count', 'payment_status')
            ->toArray();
    }

    private function shipmentStatusBreakdown(Carbon $from, Carbon $to, ?int $shippingManagerId = null): array
    {
        $q = Shipment::query()
            ->whereBetween('created_at', [$from, $to]);

        if ($shippingManagerId) {
            $q->whereHas('shipmentOrder', function (Builder $q) use ($shippingManagerId) {
                $q->where('shipping_manager_id', $shippingManagerId);
            });
        }

        return $q->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->orderBy('count','desc')
            ->pluck('count','status')
            ->toArray();
    }


    private function carriersBreakdown(Carbon $from, Carbon $to, ?int $shippingManagerId = null): array
    {
        $q = Shipment::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereNotNull('original_company_id')
            ->with('originalCompany'); // نجلب العلاقة

        if ($shippingManagerId) {
            $q->whereHas('shipmentOrder', function (Builder $q) use ($shippingManagerId) {
                $q->where('shipping_manager_id', $shippingManagerId);
            });
        }

        $rows = $q->select('original_company_id', DB::raw('COUNT(*) as count'))
            ->groupBy('original_company_id')
            ->orderByDesc('count')
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $out[] = [
                'company_id'   => $row->original_company_id,
                'company_name' => $row->originalCompany?->name ?? 'N/A',
                'shipments_count' => $row->count,
            ];
        }
        return $out;
    }


    private function timeToDeliverAvg(Carbon $from, Carbon $to, ?int $shippingManagerId = null): float
    {
        $q = Shipment::query()
            ->whereBetween('created_at', [$from, $to])
            ->whereNotNull('shipped_date')
            ->whereNotNull('delivered_date');

        if ($shippingManagerId) {
            $q->whereHas('shipmentOrder', function (Builder $q) use ($shippingManagerId) {
                $q->where('shipping_manager_id', $shippingManagerId);
            });
        }

        $rows = $q->get(['shipped_date','delivered_date']);
        if ($rows->isEmpty()) return 0.0;

        $sum = 0.0;
        foreach ($rows as $r) {
            $sum += Carbon::parse($r->shipped_date)->diffInDays(Carbon::parse($r->delivered_date));
        }
        return round($sum / max(1, $rows->count()), 2);
    }

    /* ===================== Admin KPIs ===================== */

    private function profitSummary(Carbon $from, Carbon $to): array
    {
        $q = OrderInvoice::query()->whereBetween('created_at', [$from, $to]);


return [
    'total_company_profit' => (float) $q->sum('total_company_profit'),
    'total_final_amount'   => (float) $q->sum('total_final_amount'),
];
    }

    private function complaintsSummary(Carbon $from, Carbon $to): array
    {
        $q = Complaint::query()->whereBetween('created_at', [$from, $to]);

        $total      = (clone $q)->count();
        $open       = (clone $q)->where('status', 'open')->count();
        $closed     = (clone $q)->where('status', 'closed')->count();
        $unreplied  = (clone $q)->whereNull('admin_reply')->count();
        $replied    = (clone $q)->whereNotNull('admin_reply')->count();

        return [
            'total'      => (int) $total,
            'open'       => (int) $open,
            'closed'     => (int) $closed,
            'unreplied'  => (int) $unreplied,
            'replied'    => (int) $replied,
        ];
    }

    private function costsSummary(Carbon $from, Carbon $to): array
    {
        $q = OrderInvoice::query()->whereBetween('created_at', [$from, $to]);

        return [
            'total_initial_amount' => (float) $q->sum('total_initial_amount'),
            'total_customs_fee'    => (float) $q->sum('total_customs_fee'),
            'total_service_fee'    => (float) $q->sum('total_service_fee'),
        ];
    }

}
