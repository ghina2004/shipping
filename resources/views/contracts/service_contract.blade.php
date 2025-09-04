<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Service Contract</title></head>
<body>
<h2 style="text-align:center">Service Contract</h2>
<p>Shipment Number: {{ $shipment->number }}</p>
<p>Origin Country: {{ $shipment->origin_country }} – Destination Country: {{ $shipment->destination_country }}</p>
<p>Shipping Date: {{ $shipment->shipping_date }}</p>

<hr>
<p>Service contract terms … (insert actual conditions here).</p>

<br><br><p>Customer Signature: __ Date: __</p>
</body>
</html>
