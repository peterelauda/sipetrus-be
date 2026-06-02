<?php

namespace App\Http\Controllers\Report;

use App\DTOs\Report\SalesReportDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\SalesReportRequest;
use App\Http\Resources\Report\SalesReportResource;
use App\Services\Report\ReportService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class ReportController extends Controller
{
    use ApiLogger, ApiResponser;

    protected $reportService;

    public function __construct(
        ReportService $reportService
    ) {
        $this->reportService = $reportService;
    }

    public function getSalesReport(
        SalesReportRequest $request
    ) {
        try {
            $data = $this->reportService->getSalesReport(
                SalesReportDTO::fromRequest($request)
            );

            return $this->success(
                'Get sales report successfully',
                new SalesReportResource($data),
                200
            );
        } catch (\Throwable $th) {
            $this->logError(
                'Get sales report failed: ',
                $th
            );

            return $this->error(
                'Get sales report failed',
                400,
                $th->getMessage()
            );
        }
    }
}
