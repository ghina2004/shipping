<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Services\Cart\CartService;
use App\Traits\ResponseTrait;

class CartController extends Controller
{
    use ResponseTrait;

    public function __construct(protected CartService $cartService) {}

    public function showShipmentsCart()
    {
        $user=auth()->user();

        $cart = $this->cartService->showShipmentsCart($user);

        return self::Success( new CartResource($cart), __('cart.shipments_shown'));
    }
    public function send()
    {
        $user  = auth()->user();
        $order = $this->cartService->sendCart($user);

        return self::Success(new OrderResource($order), __('cart.sent_successfully'));

    }
}
