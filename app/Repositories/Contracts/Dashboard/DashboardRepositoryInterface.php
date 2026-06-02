<?php

namespace App\Repositories\Contracts\Dashboard;

interface DashboardRepositoryInterface
{
    public function getTodaySales(int $userId);

    public function getTodayTransactions(int $userId);

    public function getTodayProfit(int $userId);

    public function getLowStockProducts(int $userId);

    public function getTopSellingProducts(int $userId);
}