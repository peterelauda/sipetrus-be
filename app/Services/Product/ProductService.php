<?php

namespace App\Services\Product;

use App\DTOs\Product\CreateProductDTO;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected $productRepository;
    protected $stockMovementRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        StockMovementRepositoryInterface $stockMovementRepository,
    ) {
        $this->productRepository = $productRepository;
        $this->stockMovementRepository = $stockMovementRepository;
    }

    public function createProduct(CreateProductDTO $dto)
    {
        $userId = auth()->id();

        $isProductExist = $this->productRepository
            ->getProductByNameAndCode($userId, $dto->name, $dto->barcode);

        if ($isProductExist) {
            throw new Exception('Product already registered');
        }

        DB::beginTransaction();

        try {
            $product = $this->productRepository->create([
                'user_id' => $userId,
                'product_code' => $this->generateProductCode($userId),
                'barcode' => $dto->barcode,
                'name' => $dto->name,
                'price' => $dto->price,
                'cost_price' => $dto->costPrice,
                'stock' => $dto->stock,
            ]);

            if ($dto->stock > 0) {
                $this->stockMovementRepository->create([
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'type' => 'in',
                    'qty' => $dto->stock,
                    'reference' => 'INITIAL_STOCK'
                ]);
            }

            DB::commit();

            return $product;
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function generateProductCode(int $userId)
    {
        $last = $this->productRepository->getLatestProduct($userId);

        $num = $last ? (int) substr($last->product_code, 1) : 0;

        return 'P' . str_pad($num + 1, 5, '0', STR_PAD_LEFT);
    }
}
