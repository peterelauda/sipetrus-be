<?php

namespace App\Repositories\Eloquents\Report;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Repositories\Contracts\Report\ReportRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface
{
    public function getTotalSales(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return Transaction::query()
            ->where('user_id', $userId)
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->sum('total');
    }

    public function getTotalTransactions(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return Transaction::query()
            ->where('user_id', $userId)
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->count();
    }

    public function getTotalProfit(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return TransactionItem::query()
            ->where('user_id', $userId)
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->select(
                DB::raw(
                    'SUM(subtotal - (cost_price * qty)) as total_profit'
                )
            )
            ->value('total_profit');
    }

    public function getItemsSold(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return TransactionItem::query()
            ->where('user_id', $userId)
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->sum('qty');
    }
}