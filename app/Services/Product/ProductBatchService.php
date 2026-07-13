<?php

namespace App\Services\Product;

use App\DTOs\Product\CreateProductBatchDTO;
use App\Repositories\Contracts\Product\ProductBatchRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductBatchService
{
    protected $productRepository;
    protected $productBatchRepository;
    protected $stockMovementRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductBatchRepositoryInterface $productBatchRepository,
        StockMovementRepositoryInterface $stockMovementRepository,
    ) {
        $this->productRepository = $productRepository;
        $this->productBatchRepository = $productBatchRepository;
        $this->stockMovementRepository = $stockMovementRepository;
    }

    public function createBatch(
        string $productId,
        CreateProductBatchDTO $dto
    ) {
        $storeId = auth()->user()->store_id;

        $product = $this->productRepository
            ->getById($productId);

        if (!$product || $product->store_id !== $storeId) {
            throw new Exception('Invalid product ID');
        }

        DB::beginTransaction();

        try {
            $batch = $this->productBatchRepository->create([
                'product_id' => $product->id,
                'batch_number' => $this->generateBatchNumber($product->id),
                'stock' => $dto->stock,
                'expired_date' => $dto->expiredDate,
            ]);

            $this->productRepository->update(
                $product->id,
                [
                    'stock' => (int) $product->stock + $dto->stock
                ]
            );

            $this->stockMovementRepository->create([
                'store_id' => $storeId,
                'product_id' => $product->id,
                'type' => 'in',
                'qty' => $dto->stock,
                'reference' => 'BATCH_STOCK'
            ]);

            DB::commit();

            return $batch;
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function getProductBatches(
        string $productId
    ) {
        $product = $this->productRepository
            ->getById($productId);

        if (
            !$product ||
            $product->store_id !== auth()->user()->store_id
        ) {
            throw new Exception('Invalid product ID');
        }

        return $this->productBatchRepository
            ->getProductBatches(
                (int) $productId
            );
    }

    public function getExpiredStocks()
    {
        $storeId = auth()->user()->store_id;

        return $this->productBatchRepository
            ->getExpiredStocks($storeId);
    }

    public function getNearExpiredStocks()
    {
        $storeId = auth()->user()->store_id;

        return $this->productBatchRepository
            ->getNearExpiredStocks($storeId);
    }

    /**
     * Generate Batch Number
     *
     * Example:
     * BATCH-1-20020818-001
     */
    public function generateBatchNumber(int $productId): string
    {
        $lastBatch = $this->productBatchRepository
            ->getLatestBatchByProduct($productId);

        $num = 0;

        if ($lastBatch) {
            $parts = explode('-', $lastBatch->batch_number);

            $num = (int) end($parts);
        }

        return sprintf(
            'BATCH-%s-%s-%03d',
            $productId,
            now()->format('Ymd'),
            $num + 1
        );
    }
}
