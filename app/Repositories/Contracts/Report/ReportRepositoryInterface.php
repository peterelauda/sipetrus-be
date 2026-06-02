<?php

namespace App\Repositories\Contracts\Report;

use Illuminate\Support\Carbon;

interface ReportRepositoryInterface
{
    public function getTotalSales(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    );

    public function getTotalTransactions(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    );

    public function getTotalProfit(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    );

    public function getItemsSold(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    );
}