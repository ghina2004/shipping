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

    use ResponseTrait;

    public function __construct(protected CartService $cartService) {}

    public function showRequestCart(): JsonResponse
    {
        $carts = $this->cartService->showRequestCart();

        return self::Success([
            'carts' => $carts
        ], __('cart.shown'));
    }

    public function employeeSubmitCart(Cart $cart): JsonResponse
    {
        $this->cartService->EmployeeSubmitCart($cart);

        return self::Success([], __('cart.submitted_by_employee'));
    }

    public function showEmployeeCart(): JsonResponse
    {
        $carts = $this->cartService->showEmployeeCart();

        return self::Success([
            'carts' => $carts
        ], __('cart.employee_carts'));
    }

    public function showCartInfo(int $cartId): JsonResponse
    {
        $cart = $this->cartService->showCartInfo($cartId);

        return self::Success([
            'cart' => $cart
        ], __('cart.shown'));
    }

    public function showShipmentsCart(int $cartId): JsonResponse
    {
        $shipments = $this->cartService->showShipmentsCart($cartId);

        return self::Success([
            'shipments' => $shipments
        ], __('cart.shipments_shown'));
    }
}
