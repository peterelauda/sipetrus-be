<?php

namespace App\Repositories\Eloquents\Product;

use App\DTOs\Stock\GetStockMovementDTO;
use App\Models\StockMovement;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class StockMovementRepository extends BaseRepository implements StockMovementRepositoryInterface
{
    protected $model;

    public function __construct(StockMovement $model)
    {
        $this->model = $model;
    }

    public function getStockMovements(GetStockMovementDTO $dto)
    {
        return $this->model
            ->with('product')
            ->where('user_id', auth()->id())
            ->when($dto->productId, function ($query) use ($dto) {
                $query->where('product_id', $dto->productId);
            })
            ->when($dto->type, function ($query) use ($dto) {
                $query->where('type', $dto->type);
            })
            ->when(
                $dto->startDate && $dto->endDate,
                function ($query) use ($dto) {
                    $query->whereBetween('created_at', [
                        $dto->startDate,
                        $dto->endDate,
                    ]);
                }
            )
            ->latest()
            ->paginate(
                perPage: $dto->limit,
                page: $dto->page
            );
    }
}