<?php

namespace App\Http\Controllers\Product;

use App\DTOs\Product\CreateProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Services\Product\ProductService;

class ProductController extends Controller
{
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

            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
