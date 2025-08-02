<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\orderResource;
use App\Http\Resources\ShipmentResource;
use App\Http\Resources\UserResource;
use App\Models\Cart;
use App\Services\Cart\CartService;
use App\Services\Order\orderService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
