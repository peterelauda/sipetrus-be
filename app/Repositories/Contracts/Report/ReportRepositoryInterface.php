<?php

namespace App\Repositories\Contracts\Report;

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
}