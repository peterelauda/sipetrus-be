<?php

namespace App\Repositories\Contracts\Dashboard;

interface DashboardRepositoryInterface
{
    public function getTodaySales(int $storeId);

    public function getTodayTransactions(int $storeId);

    public function getTodayProfit(int $storeId);

    public function getLowStockProducts(int $storeId);

    public function getTopSellingProducts(int $storeId);
}