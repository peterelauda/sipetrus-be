<?php

namespace App\Services\Supplier;

use App\DTOs\Supplier\CreateSupplierDTO;
use App\DTOs\Supplier\GetSuppliersDTO;
use App\DTOs\Supplier\UpdateSupplierDTO;
use App\Repositories\Contracts\Supplier\SupplierRepositoryInterface;

class SupplierService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private SupplierRepositoryInterface $supplierRepository,
    ) {    
    }

    /**
     * Store new supplier.
     */
    public function createSupplier(CreateSupplierDTO $dto)
    {
        return $this->supplierRepository->create([
            'store_id' => auth()->user()->store_id,
            'name' => $dto->name,
            'phone' => $dto->phone,
            'email' => $dto->email,
            'address' => $dto->address,
        ]);
    }

    /**
     * Get supplier list.
     */
    public function getSuppliers(GetSuppliersDTO $dto)
    {
        return $this->supplierRepository->getSuppliers($dto);
    }

    /**
     * Get supplier detail.
     */
    public function getSupplierById(int $id)
    {
        $supplier = $this->supplierRepository->getById($id);

        if (!$supplier) {
            throw new \Exception('Supplier not found.', 404);
        }

        if ($supplier->store_id !== auth()->user()->store_id) {
            throw new \Exception('Supplier not found.', 404);
        }

        return $supplier;
    }

    /**
     * Update supplier.
     */
    public function updateSupplier(
        int $id,
        UpdateSupplierDTO $dto
    ) {
        $supplier = $this->getSupplierById($id);

        if (!$supplier) {
            throw new \Exception('Supplier not found.', 404);
        }

        if ($supplier->store_id !== auth()->user()->store_id) {
            throw new \Exception('Supplier not found.', 404);
        }

        return $this->supplierRepository->update(
            $supplier->id,
            [
                'name' => $dto->name,
                'phone' => $dto->phone,
                'email' => $dto->email,
                'address' => $dto->address,
            ]
        );
    }

    /**
     * Delete supplier.
     */
    public function deleteSupplier(int $id)
    {
        $supplier = $this->getSupplierById($id);

        if (!$supplier) {
            throw new \Exception('Supplier not found.', 404);
        }

        if ($supplier->store_id !== auth()->user()->store_id) {
            throw new \Exception('Supplier not found.', 404);
        }

        return $this->supplierRepository->delete(
            $supplier->id
        );
    }
}
