<?php

namespace App\Repositories\Contracts\Product;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductBatchRepositoryInterface extends BaseRepositoryInterface
{
    public function getLatestBatchByProduct(int $productId);
    public function getProductBatches(int $productId);
    public function getExpiredStocks(int $storeId);
    public function getNearExpiredStocks(int $storeId);
    public function getAvailableBatchesByProduct(int $productId);
}