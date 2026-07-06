<?php

namespace App\Services\Dashboard;

use App\Repositories\Contracts\Dashboard\DashboardRepositoryInterface;

class DashboardService
{
    protected $dashboardRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        DashboardRepositoryInterface $dashboardRepository
    ) {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboard()
    {
        $storeId = auth()->user()->store_id;

        return [
            'today_sales' => $this->dashboardRepository->getTodaySales($storeId),

            'today_transactions' => $this->dashboardRepository
                ->getTodayTransactions($storeId),

            'today_profit' => $this->dashboardRepository
                ->getTodayProfit($storeId) ?? 0,

            'low_stock_products' => $this->dashboardRepository
                ->getLowStockProducts($storeId),

            'top_selling_products' => $this->dashboardRepository
                ->getTopSellingProducts($storeId),
        ];
    }
}
