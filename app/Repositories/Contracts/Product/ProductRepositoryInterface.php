<?php

namespace App\Repositories\Contracts\Product;

use App\DTOs\Product\GetProductsDTO;
use App\DTOs\Product\SearchProductDTO;
use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getLatestProduct(string $storeId);
    public function getProductByNameAndCode(string $storeId, string $productName, ?string $barcode);
    public function getProducts(string $storeId, GetProductsDTO $dto);
    public function searchProduct(SearchProductDTO $dto);
    public function getProductsByIds(array $id);
    public function getLowStockProducts(int $storeId);
}