<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    public function __construct(protected SendCartService $sendCartService) {}

    public function showRequestCart(): Collection
    {
        return Cart::query()->where('is_submit',1)->whereNull('employee_id')->get();
    }

    public function EmployeeSubmitCart(Cart $cart): void
    {
        $cart->update(['employee_id'=>auth()->id()]);
    }

    public function showEmployeeCart(): Collection
    {
        return Cart::query()->where('employee_id',auth()->user()->id)->get();
    }

    public function showCartInfo($cartId): Collection
    {
        return Cart::query()->where('id',$cartId)->get();
    }

   public function showShipmentsCart($cartId): Collection
   {
       return Shipment::query()->where('cart_id', $cartId)->get();
   }

   public function CustomerSubmitCart()
   {

   }
}
