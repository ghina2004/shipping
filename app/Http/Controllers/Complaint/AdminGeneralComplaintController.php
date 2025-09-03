<?php


namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\AdminReplyComplaintRequest;
use App\Http\Resources\Question\ComplaintResource;
use App\Models\Complaint;
use App\Services\Support\ComplaintService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class AdminGeneralComplaintController extends Controller
{
    use ResponseTrait;
    public function __construct(protected ComplaintService $service) {}

    public function index(): JsonResponse {
        $status = request('status');
        $items = $this->service->listAll($status);
        return self::Success([
            'items' => ComplaintResource::collection($items),
            'meta'  => [
                'current_page'=>$items->currentPage(),
                'last_page'   =>$items->lastPage(),
                'total'       =>$items->total(),
            ]
        ], 'Complaints listed successfully.');
    }

    public function show(Complaint $complaint): JsonResponse {
        return self::Success(new ComplaintResource($this->service->show($complaint)), 'Complaint shown successfully.');
    }

    public function reply(AdminReplyComplaintRequest $request, Complaint $complaint): JsonResponse {
        $updated = $this->service->reply(
            $complaint,
            $request->string('admin_reply'));
        return self::Success(new ComplaintResource($updated), 'Reply saved successfully.');
    }

    public function resolve(Complaint $complaint): JsonResponse
    {
        $complaint = $this->service->resolve($complaint);
        return self::Success(new ComplaintResource($complaint), 'Complaint resolved successfully.');
    }
}
