<?php

namespace App\Repositories\Eloquents\Dashboard;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Repositories\Contracts\Dashboard\DashboardRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTodaySales(int $storeId)
    {
        return Transaction::query()
            ->where('store_id', $storeId)
            ->whereDate('created_at', Carbon::today())
            ->sum('total');
    }

    public function getTodayTransactions(int $storeId)
    {
        return Transaction::query()
            ->where('store_id', $storeId)
            ->whereDate('created_at', Carbon::today())
            ->count();
    }

    public function getTodayProfit(int $storeId)
    {
        return TransactionItem::query()
            ->where('store_id', $storeId)
            ->whereDate('created_at', Carbon::today())
            ->selectRaw(
                'SUM(subtotal - (cost_price * qty)) as total_profit'
            )
            ->value('total_profit');
    }

    public function getLowStockProducts(int $storeId)
    {
        return Product::query()
            ->where('store_id', $storeId)
            ->where('stock', '<=', 5)
            ->count();
    }

    public function getTopSellingProducts(int $storeId)
    {
        return TransactionItem::query()
            ->join(
                'products',
                'products.id',
                '=',
                'transaction_items.product_id'
            )
            ->where('transaction_items.store_id', $storeId)
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(transaction_items.qty) as total_qty')
            )
            ->groupBy(
                'products.id',
                'products.name'
            )
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
    }
}