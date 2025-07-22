<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('invoice.title') }} - {{ $invoice->invoice_number }}</title>
    <style>
        @font-face {
            font-family: 'arabic';
            src: url('{{ storage_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'arabic'" : "'DejaVu Sans'" }}, sans-serif;
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            margin: 20px;
            color: #333;
        }

        header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
        }

        .section-title {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 5px 10px;
            margin-top: 20px;
            border-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 4px solid #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td, th {
            border: 1px solid #ccc;
            padding: 8px 12px;
            font-size: 14px;
        }

        .notes {
            margin-top: 20px;
            font-style: italic;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <div class="title">{{ __('invoice.title') }}</div>
    <div>{{ __('invoice.invoice_number') }}: {{ $invoice->invoice_number }}</div>
    <div>{{ __('invoice.date') }}: {{ $invoice->created_at->format('Y-m-d') }}</div>
</header>

<div class="section-title">{{ __('invoice.shipment_details') }}</div>
<table>
    <tr>
        <th>{{ __('invoice.shipment_number') }}</th>
        <td>{{ $invoice->shipment->number }}</td>
        <th>{{ __('invoice.customer') }}</th>
        <td>{{ $invoice->shipment->customer_name ?? '---' }}</td>
    </tr>
    <tr>
        <th>{{ __('invoice.shipment_date') }}</th>
        <td>{{ $invoice->shipment->created_at->format('Y-m-d') }}</td>
        <th>{{ __('invoice.invoice_type') }}</th>
        <td>
            {{ $invoice->invoice_type === 'initial'
                ? __('invoice.type_initial')
                : __('invoice.type_final') }}
        </td>
    </tr>
</table>

<div class="section-title">{{ __('invoice.financial_details') }}</div>
<table>
    <tr>
        <th>{{ __('invoice.initial_amount') }}</th>
        <td>{{ number_format($invoice->initial_amount, 2) }} $</td>
        <th>{{ __('invoice.customs_fee') }}</th>
        <td>{{ number_format($invoice->customs_fee ?? 0, 2) }} $</td>
    </tr>
    <tr>
        <th>{{ __('invoice.service_fee') }}</th>
        <td>{{ number_format($invoice->service_fee ?? 0, 2) }} $</td>
        <th>{{ __('invoice.company_profit') }}</th>
        <td>{{ number_format($invoice->company_profit ?? 0, 2) }} $</td>
    </tr>
    <tr>
        <th colspan="3">{{ __('invoice.final_amount') }}</th>
        <td><strong>{{ number_format($invoice->final_amount, 2) }} $</strong></td>
    </tr>
</table>

@if($invoice->notes)
    <div class="section-title">{{ __('invoice.notes') }}</div>
    <div class="notes">{{ $invoice->notes }}</div>
@endif

<div class="footer">
    {{ __('invoice.footer') }}
</div>

</body>
</html>
