<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportRequest;
use App\Services\Report\ReportService;
use App\Traits\ResponseTrait;

class AdminReportController extends Controller
{
    use ResponseTrait;

    public function __construct(protected ReportService $service) {}

    public function index(ReportRequest $request)
    {
        $data = $this->service->admin($request->filters());
        return self::Success($data, 'Admin reports generated successfully.');
    }
}
