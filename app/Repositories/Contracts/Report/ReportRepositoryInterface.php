<?php

namespace App\Repositories\Contracts\Report;

use App\DTOs\Report\GetReportDTO;
use Illuminate\Support\Carbon;

interface ReportRepositoryInterface
{
    public function getTotalSales(
        int $storeId,
        Carbon $startDate,
        Carbon $endDate
    );

    public function getTotalTransactions(
        int $storeId,
        Carbon $startDate,
        Carbon $endDate
    );

    public function getTotalProfit(
        int $storeId,
        Carbon $startDate,
        Carbon $endDate
    );

    public function getItemsSold(
        int $storeId,
        Carbon $startDate,
        Carbon $endDate
    );

    public function getTotalExpenses(
        int $storeId,
        GetReportDTO $dto
    );

    public function getTotalCOGS(
        int $storeId,
        GetReportDTO $dto
    );

    public function getSalesDetails(
        int $storeId,
        GetReportDTO $dto
    );

    public function getExpenseDetails(
        int $storeId,
        GetReportDTO $dto
    );

    public function getCOGSDetails(
        int $storeId,
        GetReportDTO $dto
    );
}
