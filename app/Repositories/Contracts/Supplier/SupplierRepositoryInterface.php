<?php

namespace App\Repositories\Contracts\Supplier;

use App\DTOs\Supplier\GetSuppliersDTO;
use App\Repositories\Contracts\BaseRepositoryInterface;

interface SupplierRepositoryInterface extends BaseRepositoryInterface
{
    public function getSuppliers(GetSuppliersDTO $dto);
}
