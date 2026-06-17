<?php

namespace App\Repositories\Contracts\Product;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductBatchRepositoryInterface extends BaseRepositoryInterface
{
    public function getLatestBatchByProduct(int $productId);
}