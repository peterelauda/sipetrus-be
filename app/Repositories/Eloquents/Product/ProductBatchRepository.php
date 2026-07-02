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

    public function getProductBatches(int $productId)
    {
        return $this->model
            ->where('product_id', $productId)
            ->orderBy('expired_date', 'asc')
            ->orderBy('stock', 'desc')
            ->paginate(10);
    }

    public function getExpiredStocks(int $storeId)
    {
        return $this->model
            ->with('product')
            ->whereHas('product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->where('stock', '>', 0)
            ->whereDate(
                'expired_date',
                '<',
                today()
            )
            ->orderBy('expired_date')
            ->paginate(10);
    }

    public function getNearExpiredStocks(int $storeId)
    {
        return $this->model
            ->with('product')
            ->whereHas('product', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->where('stock', '>', 0)
            ->whereBetween(
                'expired_date',
                [
                    today(),
                    today()->addDays(30)
                ]
            )
            ->orderBy('expired_date')
            ->paginate(10);
    }

    public function getAvailableBatchesByProduct(
        int $productId
    ) {
        return $this->model
            ->where('product_id', $productId)
            ->where('stock', '>', 0)
            ->whereDate('expired_date', '>=', today())
            ->orderBy('expired_date')
            ->lockForUpdate()
            ->get();
    }
}