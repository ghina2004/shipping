<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Collection;

class CartService
{

   public function showEmployeeCarts(): Collection
   {
       return Cart::query()->where('employee_id',auth()->user()->id)->get();
   }

    public function showCart($cartId): Collection
    {
        return Cart::query()->where('id',$cartId)->with('cartShipments.shipmentCart')->get();
    }

   public function showShipmentsCart($cartId): Collection
   {
       return Shipment::query()->where('cart_id', $cartId)->get();
   }


}
