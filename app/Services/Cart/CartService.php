<?php

namespace App\Services\Cart;

use App\Exceptions\Types\CustomException;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartService
{
    public function createCart(User $user): void
    {
        Cart::query()->create(['customer_id'=>$user['id']]);
    }

    public function showShipmentsCart($user)
    {
        $cart = $user->cart;

        if (!$cart) {
            throw new ModelNotFoundException('Cart not found.');
        }

        return $cart->with('shipments')->get();
    }

    public function sendCart($user)
    {
        return DB::transaction(function () use ($user) {
            $cart = $user->cart;

            if (!$cart) {
                throw new ModelNotFoundException('Cart not found.');
            }

            $order = Order::create([
                'customer_id' => $user->id,
                'order_number' => Str::upper(Str::random(8)),
            ]);

            $shipments = $cart->shipments;

            if ($shipments->isEmpty()) {
                throw new CustomException('Your cart is empty.', 422);
            }

            foreach ($shipments as $shipment) {
                $shipment->update([
                    'order_id' => $order->id,
                    'cart_id' => null,
                ]);
            }

            return $order->load('shipments');
        });
    }
}

