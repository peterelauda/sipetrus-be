<?php

namespace App\Repositories\Eloquents\Product;

use App\Models\StockMovement;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class StockMovementRepository extends BaseRepository implements StockMovementRepositoryInterface
{
    protected $model;

    public function __construct(StockMovement $model)
    {
        $this->model = $model;
    }
}