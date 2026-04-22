<?php

namespace App\Http\Controllers\Product;

use App\DTOs\Product\CreateProductDTO;
use App\DTOs\Product\GetProductsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\GetProductsRequest;
use App\Http\Resources\Product\CreateProductResource;
use App\Http\Resources\Product\GetProductsResource;
use App\Services\Product\ProductService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class ProductController extends Controller
{
    use ApiLogger, ApiResponser;

    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function createProduct(CreateProductRequest $request)
    {
        try {
            $data = $this->productService
                ->createProduct(CreateProductDTO::fromRequest($request));

            return $this->success(
                'Store product data successfully',
                new CreateProductResource($data),
                200
            );
        } catch (\Throwable $th) {
            $this->logError('Failed to create: ', $th);

            return $this->error('Failed to store product data', 400, $th->getMessage());
        }
    }

    public function getProducts(GetProductsRequest $request)
    {
        try {
            $data = $this->productService
                ->getProducts(GetProductsDTO::fromRequest($request));

            return $this->success(
                'Get products data successfully',
                GetProductsResource::collection($data),
                200
            );
        } catch (\Throwable $th) {
            $this->logError('Get products data failed: ', $th);

            return $this->error('Get products data failed', 400, $th->getMessage());
        }
    }

    public function getProductById($id)
    {
        try {
            $data = $this->productService->getProductById($id);

            return $this->success(
                'Get product data successfully',
                new GetProductsResource($data),
                200
            );
        } catch (\Throwable $th) {
            $this->logError('Get product data failed: ', $th);

            return $this->error('Get product data failed', 400, $th->getMessage());
        }
    }
}
