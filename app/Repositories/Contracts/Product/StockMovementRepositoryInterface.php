<?php

namespace App\Repositories\Contracts\Product;

use App\DTOs\Stock\GetStockMovementDTO;
use App\Repositories\Contracts\BaseRepositoryInterface;

interface StockMovementRepositoryInterface extends BaseRepositoryInterface
{
    public function getStockMovements(GetStockMovementDTO $dto);
}