<?php

namespace App\Providers;

use App\Repositories\Contracts\Expense\ExpenseRepositoryInterface;
use App\Repositories\Eloquents\Expense\ExpenseRepository;
use App\Repositories\Contracts\Transaction\TxItemBatchRepositoryInterface;
use App\Repositories\Eloquents\Transaction\TxItemBatchRepository;
use App\Repositories\Contracts\Product\ProductBatchRepositoryInterface;
use App\Repositories\Eloquents\Product\ProductBatchRepository;
use App\Repositories\Contracts\Report\ReportRepositoryInterface;
use App\Repositories\Eloquents\Report\ReportRepository;
use App\Repositories\Contracts\Dashboard\DashboardRepositoryInterface;
use App\Repositories\Eloquents\Dashboard\DashboardRepository;
use App\Repositories\Contracts\Transaction\TransactionItemRepositoryInterface;
use App\Repositories\Eloquents\Transaction\TransactionItemRepository;
use App\Repositories\Contracts\Transaction\TransactionRepositoryInterface;
use App\Repositories\Eloquents\Transaction\TransactionRepository;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;
use App\Repositories\Eloquents\Product\StockMovementRepository;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Eloquents\Product\ProductRepository;
use App\Repositories\Contracts\Authentication\UserRepositoryInterface;
use App\Repositories\Eloquents\Authentication\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(TxItemBatchRepositoryInterface::class, TxItemBatchRepository::class);
        $this->app->bind(ProductBatchRepositoryInterface::class, ProductBatchRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(TransactionItemRepositoryInterface::class, TransactionItemRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(StockMovementRepositoryInterface::class, StockMovementRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
