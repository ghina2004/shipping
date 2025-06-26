<?php

namespace App\Services\Cart;

use App\Models\Cart;

class SendCartService
{
    public function sendToShipmentManager(Cart $cart): void
    {
        $cart->update(['shipment_status'=>1]);
    }

    public function sendToAccountant(Cart $cart): void
    {
        $cart->update(['accountant_status'=>1]);
    }
}
