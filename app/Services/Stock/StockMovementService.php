<?php

namespace App\Services\Stock;

use App\DTOs\Stock\GetStockMovementDTO;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;

class StockMovementService
{
    protected $stockMovementRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(StockMovementRepositoryInterface $stockMovementRepository)
    {
        $this->stockMovementRepository = $stockMovementRepository;
    }

    public function getStockMovements(GetStockMovementDTO $dto)
    {
        return $this->stockMovementRepository->getStockMovements($dto);
    }
}
