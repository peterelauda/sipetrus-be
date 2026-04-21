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
        $this->model->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->first();
    }
}