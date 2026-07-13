<?php

namespace App\Services\Report;

use App\DTOs\Report\GetReportDTO;
use App\DTOs\Report\SalesReportDTO;
use App\Repositories\Contracts\Report\ReportRepositoryInterface;
use Illuminate\Support\Carbon;

class ReportService
{
    protected $reportRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        ReportRepositoryInterface $reportRepository
    ) {
        $this->reportRepository = $reportRepository;
    }

    public function getSalesReport(
        SalesReportDTO $dto
    ): array {
        $storeId = auth()->user()->store_id;

        return [
            'total_sales' => $this->reportRepository
                ->getTotalSales(
                    $storeId,
                    $dto->startDate,
                    $dto->endDate
                ),

            'total_transactions' => $this->reportRepository
                ->getTotalTransactions(
                    $storeId,
                    $dto->startDate,
                    $dto->endDate
                ),

            'total_profit' => $this->reportRepository
                ->getTotalProfit(
                    $storeId,
                    $dto->startDate,
                    $dto->endDate
                ) ?? 0,

            'items_sold' => $this->reportRepository
                ->getItemsSold(
                    $storeId,
                    $dto->startDate,
                    $dto->endDate
                ),
        ];
    }

    public function getCashFlow(
        GetReportDTO $dto
    ) {
        $storeId = auth()->user()->store_id;

        $sales = $this->reportRepository
            ->getTotalSales(
                $storeId,
                Carbon::parse($dto->startDate),
                Carbon::parse($dto->endDate)
            );

        $expenses = $this->reportRepository
            ->getTotalExpenses(
                $storeId,
                $dto
            );

        return [
            'total_sales' => (float) $sales,
            'total_expenses' => (float) $expenses,
            'net_cash_flow' => (float) ($sales - $expenses),
        ];
    }

    public function getProfitLoss(
        GetReportDTO $dto
    ) {
        $storeId = auth()->user()->store_id;

        $revenue = $this->reportRepository
            ->getTotalSales(
                $storeId,
                Carbon::parse($dto->startDate),
                Carbon::parse($dto->endDate)
            );

        $cogs = $this->reportRepository
            ->getTotalCOGS(
                $storeId,
                $dto
            );

        $expenses = $this->reportRepository
            ->getTotalExpenses(
                $storeId,
                $dto
            );

        return [
            'revenue' => (float) $revenue,
            'cogs' => (float) $cogs,
            'gross_profit' => (float) ($revenue - $cogs),
            'expenses' => (float) $expenses,
            'net_profit' => (float) (
                $revenue - $cogs - $expenses
            ),
        ];
    }

    public function exportCashFlow(
        GetReportDTO $dto
    ) {
        $storeId = auth()->user()->store_id;

        $sales = $this->reportRepository
            ->getTotalSales(
                $storeId,
                Carbon::parse($dto->startDate),
                Carbon::parse($dto->endDate)
            );

        $expenses = $this->reportRepository
            ->getTotalExpenses(
                $storeId,
                $dto
            );

        return [
            'summary' => [
                'total_sales' => $sales,
                'total_expenses' => $expenses,
                'net_cash_flow' => $sales - $expenses,
            ],

            'sales_details' => $this->reportRepository
                ->getSalesDetails(
                    $storeId,
                    $dto
                ),

            'expense_details' => $this->reportRepository
                ->getExpenseDetails(
                    $storeId,
                    $dto
                ),
        ];
    }

    public function exportProfitLoss(
        GetReportDTO $dto
    ) {
        $storeId = auth()->user()->store_id;

        return [
            'summary' => [
                'revenue' => $this->reportRepository
                    ->getTotalSales(
                        $storeId,
                        Carbon::parse($dto->startDate),
                        Carbon::parse($dto->endDate)
                    ),

                'cogs' => $this->reportRepository
                    ->getTotalCOGS($storeId, $dto),

                'expenses' => $this->reportRepository
                    ->getTotalExpenses($storeId, $dto),
            ],

            'sales_details' => $this->reportRepository
                ->getSalesDetails($storeId, $dto),

            'cogs_details' => $this->reportRepository
                ->getCOGSDetails($storeId, $dto),

            'expense_details' => $this->reportRepository
                ->getExpenseDetails($storeId, $dto),
        ];
    }
}
