<?php

namespace App\Repositories\Eloquents\Product;

use App\Models\Product;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getLatestProduct(string $userId)
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->first();
    }

    public function getProductByNameAndCode(
        string $userId,
        string $productName,
        string $barcode
    ) {
        return $this->model
            ->where('user_id', $userId)
            ->where('name', 'LIKE', '%' . $productName . '%')
            ->where('barcode', $barcode)
            ->first();
    }
}