<?php

namespace App\Http\Controllers\Stock;

use App\DTOs\Stock\GetStockMovementDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\GetStockMovementRequest;
use App\Http\Resources\Stock\GetStockMovementResource;
use App\Services\Stock\StockMovementService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class StockMovementController extends Controller
{
    use ApiLogger, ApiResponser;

    protected $stockMovementService;

    public function __construct(StockMovementService $stockMovementService)
    {
        $this->stockMovementService = $stockMovementService;
    }

    public function getStockMovements(GetStockMovementRequest $request)
    {
        try {
            $data = $this->stockMovementService
                ->getStockMovements(GetStockMovementDTO::fromRequest($request));

            return $this->success('Stock movement data retrieved successfully', GetStockMovementResource::collection($data), 200);
        } catch (\Throwable $th) {
            $this->logError('Get stock movement data failed: ', $th);

            return $this->error('Get stock movement data failed', 400, $th->getMessage());
        }
    }
}
