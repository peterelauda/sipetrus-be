<?php

namespace App\Repositories\Eloquents\Product;

use App\DTOs\Product\GetProductsDTO;
use App\DTOs\Product\SearchProductDTO;
use App\Models\Product;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getLatestProduct(string $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('barcode', 'LIKE', '%P%')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->first();
    }

    public function getProductByNameAndCode(
        string $userId,
        string $productName,
        ?string $barcode
    ) {
        return $this->model
            ->where('user_id', $userId)
            ->where('name', 'LIKE', '%' . $productName . '%')
            ->when($barcode, function ($q) use ($barcode) {
                $q->where('barcode', $barcode);
            })
            ->first();
    }

    public function getProducts(string $userId, GetProductsDTO $dto)
    {
        return $this->model
            ->where('user_id', $userId)
            ->when($dto->name, function ($q, $name) {
                $q->where('name', 'like', '%' . $name . '%');
            })
            ->when($dto->barcode, function ($q, $barcode) {
                $q->where('barcode', $barcode);
            })
            ->paginate($dto->perPage ?? 10);
    }

    public function searchProduct(SearchProductDTO $dto)
    {
        return Product::search($dto->keyword)
            ->where('user_id', auth()->id())
            ->paginate(10);
    }

    public function getProductsByIds(array $id)
    {
        return $this->model
            ->whereIn('id', $id)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
    }
}