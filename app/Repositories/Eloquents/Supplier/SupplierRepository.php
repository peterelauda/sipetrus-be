<?php

namespace App\Repositories\Eloquents\Supplier;

use App\DTOs\Supplier\GetSuppliersDTO;
use App\Models\Supplier;
use App\Repositories\Contracts\Supplier\SupplierRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    public function __construct(Supplier $model)
    {
        parent::__construct($model);
    }

    public function getSuppliers(GetSuppliersDTO $dto)
    {
        return $this->query()
            ->where('store_id', auth()->user()->store_id)
            ->when(
                $dto->keyword,
                function ($query) use ($dto) {
                    $query->where(function ($q) use ($dto) {
                        $q->where('name', 'like', "%{$dto->keyword}%")
                            ->orWhere('phone', 'like', "%{$dto->keyword}%")
                            ->orWhere('email', 'like', "%{$dto->keyword}%");
                    });
                }
            )
            ->orderBy('name')
            ->paginate($dto->perPage);
    }
}
