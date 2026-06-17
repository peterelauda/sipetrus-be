<?php

namespace App\Services\Report;

use App\DTOs\Report\SalesReportDTO;
use App\Repositories\Contracts\Report\ReportRepositoryInterface;

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
}
