<?php

namespace App\Services\Product;

use App\DTOs\Product\AdjustStockDTO;
use App\DTOs\Product\CreateProductDTO;
use App\DTOs\Product\GetProductsDTO;
use App\DTOs\Product\SearchProductDTO;
use App\DTOs\Product\UpdateProductDTO;
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
        $storeId = auth()->user()->store_id;

        $isProductExist = $this->productRepository
            ->getProductByNameAndCode($storeId, $dto->name, $dto->barcode);

        if ($isProductExist) {
            throw new Exception('Product already registered');
        }

        DB::beginTransaction();

        try {
            $product = $this->productRepository->create([
                'store_id' => $storeId,
                'barcode' => $dto->barcode ?? $this->generateProductCode($storeId),
                'name' => $dto->name,
                'price' => $dto->price,
                'cost_price' => $dto->costPrice,
                'stock' => $dto->stock,
                'category' => $dto->category,
            ]);

            if ($dto->stock > 0) {
                $this->stockMovementRepository->create([
                    'store_id' => $storeId,
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

    public function getProducts(GetProductsDTO $dto)
    {
        $storeId = auth()->user()->store_id;

        $products = $this->productRepository->getProducts($storeId, $dto);

        return $products;
    }

    public function getProductById(string $id)
    {
        $storeId = auth()->user()->store_id;

        $product = $this->productRepository
            ->getById($id);

        if (!$product || $product->store_id !== $storeId) {
            throw new Exception('Invalid product ID');
        }

        return $product;
    }

    public function searchProduct(SearchProductDTO $dto)
    {
        $product = $this->productRepository->searchProduct($dto);

        return $product;
    }

    public function updateProductById(string $id, UpdateProductDTO $dto)
    {
        $storeId = auth()->user()->store_id;

        $product = $this->productRepository
            ->getById($id);

        if (!$product || $product->store_id !== $storeId) {
            throw new Exception('Invalid product ID');
        }

        return $this->productRepository->update($id, [
            'name' => $dto->name,
            'price' => $dto->price,
            'category' => $dto->category,
            'cost_price' => $dto->costPrice,
            'barcode' => $dto->barcode
        ]);
    }

    public function adjustStock(string $id, AdjustStockDTO $dto)
    {
        $storeId = auth()->user()->store_id;

        $product = $this->productRepository
            ->getById($id);

        if (!$product || $product->store_id !== $storeId) {
            throw new Exception('Invalid product ID');
        }

        DB::beginTransaction();

        try {
            if ($dto->type == 'in') {
                $this->productRepository->update($id, [
                    'stock' => (int) $product->stock + $dto->qty
                ]);

                $stockMovement = $this->stockMovementRepository->create([
                    'store_id' => $storeId,
                    'product_id' => $id,
                    'type' => $dto->type,
                    'qty' => $dto->qty,
                    'reference' => 'MANUAL_ADJUSTMENT'
                ]);

                DB::commit();

                return $stockMovement;
            } else {
                $this->productRepository->update($id, [
                    'stock' => (int) $product->stock - $dto->qty
                ]);

                $stockMovement = $this->stockMovementRepository->create([
                    'store_id' => $storeId,
                    'product_id' => $id,
                    'type' => $dto->type,
                    'qty' => $dto->qty,
                    'reference' => 'DAMAGED_PRODUCT'
                ]);

                DB::commit();

                return $stockMovement;
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function getLowStockProducts()
    {
        $storeId = auth()->user()->store_id;

        return $this->productRepository
            ->getLowStockProducts($storeId);
    }

    public function deleteProductById(string $id)
    {
        $storeId = auth()->user()->store_id;

        $product = $this->productRepository
            ->getById($id);

        if (!$product || $product->store_id !== $storeId) {
            throw new Exception('Invalid product ID');
        }

        $this->productRepository->delete($id);
    }

    public function generateProductCode(int $storeId)
    {
        $last = $this->productRepository->getLatestProduct($storeId);

        $num = $last ? (int) substr($last->barcode, 1) : 0;

        return 'P' . str_pad($num + 1, 5, '0', STR_PAD_LEFT);
    }
}
