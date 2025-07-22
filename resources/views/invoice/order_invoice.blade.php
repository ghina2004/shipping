<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('invoice.order_invoice_title') }} #{{ $order->id }}</title>
    <style>
        @font-face {
            font-family: 'DejaVuSans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}');
        }
        body {
            font-family: 'DejaVuSans', sans-serif;
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            margin: 20px;
            color: #333;
        }
        .header, .footer {
            text-align: center;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        .section-title {
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>{{ __('invoice.order_invoice_title') }} #{{ $order->id }}</h2>
    <p>{{ __('invoice.date') }}: {{ $order->created_at->format('Y-m-d') }}</p>
</div>

<div class="section-title">{{ __('invoice.shipments_summary') }}</div>
<table>
    <thead>
    <tr>
        <th>{{ __('invoice.shipment_id') }}</th>
        <th>{{ __('invoice.initial_amount') }}</th>
        <th>{{ __('invoice.customs_fee') }}</th>
        <th>{{ __('invoice.service_fee') }}</th>
        <th>{{ __('invoice.company_profit') }}</th>
        <th>{{ __('invoice.final_amount') }}</th>
    </tr>
    </thead>
    <tbody>
    @php $total = 0; @endphp
    @foreach($order->shipments as $shipment)
        @php
            $invoice = $shipment->invoice;
            $total += $invoice->final_amount ?? 0;
        @endphp
        <tr>
            <td>{{ $shipment->id }}</td>
            <td>{{ number_format($invoice->initial_amount ?? 0, 2) }}</td>
            <td>{{ number_format($invoice->customs_fee ?? 0, 2) }}</td>
            <td>{{ number_format($invoice->service_fee ?? 0, 2) }}</td>
            <td>{{ number_format($invoice->company_profit ?? 0, 2) }}</td>
            <td>{{ number_format($invoice->final_amount ?? 0, 2) }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5"><strong>{{ __('invoice.total') }}</strong></td>
        <td><strong>{{ number_format($total, 2) }}</strong></td>
    </tr>
    </tbody>
</table>

<div class="footer">
    {{ __('invoice.footer') }}
</div>

</body>
</html>
