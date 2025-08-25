<?php

namespace App\Http\Controllers\Rate;

use App\Http\Requests\Rate\RateOrderRequest;
use App\Http\Resources\RateResource;
use App\Models\Order;
use App\Services\Rate\RateOrderService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class RateOrderController
{
    use ResponseTrait;

    public function __construct(protected RateOrderService $service){}

    public function store(RateOrderRequest $request, Order $order): \Illuminate\Http\JsonResponse
    {
        $rate = $this->service->rate($order, $request->validated());

        return self::Success(new RateResource($rate), 'Order rated successfully.');
    }

    public function show(Order $order): JsonResponse
    {
        $rate = $this->service->showMyRate($order);

        return self::Success(
            $rate ? new RateResource($rate) : null,
            'Order rate fetched successfully.'
        );
    }
}
