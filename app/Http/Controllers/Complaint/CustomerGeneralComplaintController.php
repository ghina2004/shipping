<?php


namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\StoreGeneralComplaintRequest;
use App\Http\Resources\Question\ComplaintResource;
use App\Models\Complaint;
use App\Services\Support\ComplaintService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class CustomerGeneralComplaintController extends Controller
{
    use ResponseTrait;
    public function __construct(protected ComplaintService $service) {}

    public function index(): JsonResponse {
        $items = $this->service->listForCustomer(auth()->user());
        return self::Success(ComplaintResource::collection($items), 'Complaints listed successfully.');
    }

    public function store(StoreGeneralComplaintRequest $request): JsonResponse {
        $complaint = $this->service->create(auth()->user(), $request->validated());
        return self::Success(new ComplaintResource($complaint), 'Complaint submitted successfully.');
    }

    public function show(int $id): JsonResponse {
        $complaint = Complaint::findOrFail($id);
        $complaint = $this->service->showForCustomer(auth()->user(), $complaint);
        return self::Success(new ComplaintResource($complaint), 'Complaint shown successfully.');
    }
}
