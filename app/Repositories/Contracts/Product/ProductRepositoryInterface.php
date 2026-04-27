<?php

namespace App\Repositories\Contracts\Product;

use App\DTOs\Product\GetProductsDTO;
use App\DTOs\Product\SearchProductDTO;
use App\Repositories\Contracts\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getLatestProduct(string $userId);
    public function getProductByNameAndCode(string $userId, string $productName, ?string $barcode);
    public function getProducts(string $userId, GetProductsDTO $dto);
    public function searchProduct(SearchProductDTO $dto);
}