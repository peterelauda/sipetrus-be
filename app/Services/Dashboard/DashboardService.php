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
        $userId = auth()->id();

        return [
            'today_sales' => $this->dashboardRepository->getTodaySales($userId),

            'today_transactions' => $this->dashboardRepository
                ->getTodayTransactions($userId),

            'today_profit' => $this->dashboardRepository
                ->getTodayProfit($userId) ?? 0,

            'low_stock_products' => $this->dashboardRepository
                ->getLowStockProducts($userId),

            'top_selling_products' => $this->dashboardRepository
                ->getTopSellingProducts($userId),
        ];
    }
}
