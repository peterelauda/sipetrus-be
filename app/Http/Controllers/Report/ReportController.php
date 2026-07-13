<?php

namespace App\Http\Controllers\Report;

use App\DTOs\Report\GetReportDTO;
use App\DTOs\Report\SalesReportDTO;
use App\Exports\CashFlowExport;
use App\Exports\ProfitLossExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\GetReportRequest;
use App\Http\Requests\Report\SalesReportRequest;
use App\Http\Resources\Report\SalesReportResource;
use App\Services\Report\ReportService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;
use Maatwebsite\Excel\Facades\Excel;

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

    public function getCashFlow(
        GetReportRequest $request
    ) {
        $data = $this->reportService
            ->getCashFlow(
                GetReportDTO::fromRequest($request)
            );

        return $this->success(
            'Cash flow report retrieved successfully',
            $data
        );
    }

    public function getProfitLoss(
        GetReportRequest $request
    ) {
        $data = $this->reportService
            ->getProfitLoss(
                GetReportDTO::fromRequest($request)
            );

        return $this->success(
            'Profit loss report retrieved successfully',
            $data
        );
    }

    public function exportCashFlow(
        GetReportRequest $request
    ) {
        $data = $this->reportService
            ->exportCashFlow(
                GetReportDTO::fromRequest($request)
            );

        return Excel::download(
            new CashFlowExport($data),
            "cash-flow-report-from-{$request->start_date}-to-{$request->end_date}.xlsx"
        );
    }

    public function exportProfitLoss(
        GetReportRequest $request
    ) {
        $data = $this->reportService
            ->exportProfitLoss(
                GetReportDTO::fromRequest($request)
            );

        return Excel::download(
            new ProfitLossExport($data),
            "profit-loss-report-from-{$request->start_date}-to-{$request->end_date}.xlsx"
        );
    }
}
