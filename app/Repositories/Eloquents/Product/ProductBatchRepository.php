<?php

namespace App\Repositories\Eloquents\Product;

use App\Models\ProductBatch;
use App\Repositories\Contracts\Product\ProductBatchRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class ProductBatchRepository extends BaseRepository implements ProductBatchRepositoryInterface
{
    public function __construct(ProductBatch $model)
    {
        $this->model = $model;
    }

    public function getLatestBatchByProduct(int $productId)
    {
        return $this->model
            ->where('product_id', $productId)
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->first();
    }
}