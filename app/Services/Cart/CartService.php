<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    public function createCart($userId)
    {
        return Cart::query()->create(['customer_id',$userId]);
    }

    public function showCartInfo($cartId): Collection
    {
        return Cart::query()->where('id',$cartId)->get();
    }

   public function showShipmentsCart($cartId): Collection
   {
       return Shipment::query()->where('cart_id', $cartId)->get();
   }

}
