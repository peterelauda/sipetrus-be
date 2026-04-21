<?php

namespace App\Providers;

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
