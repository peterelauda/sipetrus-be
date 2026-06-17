<?php

namespace App\Http\Controllers\Product;

use App\DTOs\Product\CreateProductBatchDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductBatchRequest;
use App\Http\Resources\Product\ProductBatchResource;
use App\Services\Product\ProductBatchService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class ProductBatchController extends Controller
{
    use ApiLogger, ApiResponser;

    private ProductBatchService $productBatchService;

    public function __construct(
        ProductBatchService $productBatchService
    ) {
        $this->productBatchService = $productBatchService;
    }

    /**
     * Create Product Batch
     */
    public function createBatch(int $id, CreateProductBatchRequest $request)
    {
        try {
            $batch = $this->productBatchService
                ->createBatch(
                    $id,
                    CreateProductBatchDTO::fromRequest($request)
                );

            return $this->success(
                'Product batch created successfully',
                new ProductBatchResource($batch),
                200
            );
        } catch (\Throwable $th) {
            $this->logError(
                'Failed to create product batch: ',
                $th
            );

            return $this->error(
                'Failed to create product batch',
                400,
                $th->getMessage()
            );
        }
    }
}
