<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\ShipmentResource;
use App\Http\Resources\UserResource;
use App\Services\Cart\CartService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ResponseTrait;

    public function __construct(protected CartService $cartService) {}

    public function showEmployeeCarts(): JsonResponse
    {
        $carts = $this->cartService->showEmployeeCarts();

        return self::Success([
            'cart' => CartResource::collection($carts),
        ], __('cart.employee_carts_listed'));
    }

    public function showCart($cartId): JsonResponse
    {
        $carts = $this->cartService->showCart($cartId);

        return self::Success([
            'cart' => CartResource::collection($carts),
        ], __('cart.cart_details_retrieved'));
    }

    public function showShipmentsCart($cartId): JsonResponse
    {
        $shipments = $this->cartService->showShipmentsCart($cartId);
        return self::Success([
            'shipments' => ShipmentResource::collection($shipments),
        ], __('cart.shipments_listed'));
    }

}
