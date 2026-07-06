<?php

namespace App\Repositories\Eloquents\Report;

use App\DTOs\Report\GetReportDTO;
use App\Enums\PaymentStatusEnum;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Repositories\Contracts\Report\ReportRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface
{
    public function getTotalSales(
        int $storeId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return Transaction::query()
            ->where('store_id', $storeId)
            ->where('status', 'PAID')
            ->when(
                $startDate && $endDate,
                fn($q) => $q->whereBetween(
                    'created_at',
                    [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59',
                    ]
                )
            )
            ->sum('total');
    }

    public function getTotalTransactions(
        int $storeId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return Transaction::query()
            ->where('store_id', $storeId)
            ->where('status', 'PAID')
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->count();
    }

    public function getTotalProfit(
        int $storeId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return TransactionItem::query()
            ->where('store_id', $storeId)
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
        int $storeId,
        Carbon $startDate,
        Carbon $endDate
    ) {
        return TransactionItem::query()
            ->where('store_id', $storeId)
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->sum('qty');
    }

    public function getTotalExpenses(
        int $storeId,
        GetReportDTO $dto
    ) {
        return Expense::query()
            ->where('store_id', $storeId)
            ->when(
                $dto->startDate && $dto->endDate,
                fn($q) => $q->whereBetween(
                    'expense_date',
                    [
                        $dto->startDate,
                        $dto->endDate,
                    ]
                )
            )
            ->sum('amount');
    }

    public function getTotalCOGS(
        int $storeId,
        GetReportDTO $dto
    ) {
        return TransactionItem::query()
            ->join(
                'transactions',
                'transaction_items.transaction_id',
                '=',
                'transactions.id'
            )
            ->where(
                'transactions.store_id',
                $storeId
            )
            ->where(
                'transactions.status',
                PaymentStatusEnum::PAID
            )
            ->when(
                $dto->startDate && $dto->endDate,
                function ($query) use ($dto) {
                    $query->whereBetween(
                        'transactions.created_at',
                        [
                            $dto->startDate . ' 00:00:00',
                            $dto->endDate . ' 23:59:59',
                        ]
                    );
                }
            )
            ->selectRaw(
                'COALESCE(SUM(transaction_items.qty * transaction_items.cost_price), 0) as total'
            )
            ->value('total');
    }

    public function getSalesDetails(
        int $storeId,
        GetReportDTO $dto
    ) {
        return Transaction::query()
            ->where('store_id', $storeId)
            ->where('status', PaymentStatusEnum::PAID)
            ->when(
                $dto->startDate && $dto->endDate,
                fn($q) => $q->whereBetween(
                    'created_at',
                    [
                        $dto->startDate . ' 00:00:00',
                        $dto->endDate . ' 23:59:59',
                    ]
                )
            )
            ->select([
                'invoice_number',
                'payment_method',
                'total',
                'created_at'
            ])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getExpenseDetails(
        int $storeId,
        GetReportDTO $dto
    ) {
        return Expense::query()
            ->where('store_id', $storeId)
            ->when(
                $dto->startDate && $dto->endDate,
                fn($q) => $q->whereBetween(
                    'expense_date',
                    [
                        $dto->startDate,
                        $dto->endDate,
                    ]
                )
            )
            ->orderByDesc('expense_date')
            ->get([
                'category',
                'description',
                'amount',
                'expense_date'
            ]);
    }

    public function getCOGSDetails(
        int $storeId,
        GetReportDTO $dto
    ) {
        return TransactionItem::query()
            ->with([
                'product:id,name',
                'transaction:id,invoice_number,created_at'
            ])
            ->whereHas(
                'transaction',
                function ($q) use ($storeId) {
                    $q->where('store_id', $storeId)
                        ->where(
                            'status',
                            PaymentStatusEnum::PAID
                        );
                }
            )
            ->when(
                $dto->startDate && $dto->endDate,
                function ($q) use ($dto) {
                    $q->whereHas(
                        'transaction',
                        function ($transaction) use ($dto) {
                            $transaction->whereBetween(
                                'created_at',
                                [
                                    $dto->startDate . ' 00:00:00',
                                    $dto->endDate . ' 23:59:59',
                                ]
                            );
                        }
                    );
                }
            )
            ->get();
    }
}
