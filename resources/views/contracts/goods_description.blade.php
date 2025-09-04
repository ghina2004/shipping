<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Goods Description</title></head>
<body>
<h2 style="text-align:center">Goods Description</h2>
<p>Shipment Number: {{ $shipment->number }}</p>
<p>Weight: {{ $shipment->cargo_weight }} – Shipping Method: {{ $shipment->shipping_method }}</p>
<p>Additional details provided by the system/employee …</p>
</body>
</html>
