<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRouteRequest;
use App\Http\Requests\OriginalShippingCompanyRequest;
use App\Http\Requests\UpdateOrderRouteStatusRequest;
use App\Http\Resources\OriginalShippingCompanyResource;
use App\Models\Order;
use App\Models\OrdertRoute;
use App\Models\OriginalShippingCompany;
use App\Services\OriginalShippingCompanyService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class OrderRouteController extends Controller
{
    use ResponseTrait;

    public function __construct(private OrderRouteService $service) {}

    public function index()    { return $this->service->index(); }
    public function show(OrdertRoute $orderRoute)   { return $this->service->show($orderRoute); }
    public function store(OrderRouteRequest $request)   { return $this->service->store($request); }
    public function update(OrderRouteRequest $request, OrdertRoute $orderRoute) { return $this->service->update($request, $orderRoute); }
    public function destroy(OrdertRoute $orderRoute) { return $this->service->destroy($orderRoute); }
    public function updateStatus(UpdateOrderRouteStatusRequest $request, OrdertRoute $route)
    {
        return $this->service->updateStatus($route, $request->status);
    }
}
