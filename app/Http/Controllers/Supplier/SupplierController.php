<?php

namespace App\Http\Controllers\Supplier;

use App\DTOs\Supplier\CreateSupplierDTO;
use App\DTOs\Supplier\GetSuppliersDTO;
use App\DTOs\Supplier\UpdateSupplierDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Requests\Supplier\GetSuppliersRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Supplier\SupplierResource;
use App\Services\Supplier\SupplierService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class SupplierController extends Controller
{
    use ApiResponser, ApiLogger;

    public function __construct(
        private SupplierService $supplierService,
    ) {
    }

    /**
     * Create supplier.
     */
    public function createSupplier(CreateSupplierRequest $request)
    {
        try {
            $supplier = $this->supplierService->createSupplier(
                CreateSupplierDTO::fromRequest($request)
            );

            return $this->success(
                'Create supplier successfully',
                new SupplierResource($supplier),
                201
            );
        } catch (\Exception $e) {
            $this->logError('Failed when create supplier', $e);

            return $this->error(
                'Failed when create supplier',
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    /**
     * Get supplier list.
     */
    public function getSuppliers(GetSuppliersRequest $request)
    {
        try {
            $suppliers = $this->supplierService->getSuppliers(
                GetSuppliersDTO::fromRequest($request)
            );

            return $this->success(
                'Get suppliers successfully',
                SupplierResource::collection($suppliers)
            );
        } catch (\Exception $e) {
            $this->logError('Failed when get suppliers', $e);

            return $this->error(
                'Failed when get suppliers',
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    /**
     * Get supplier detail.
     */
    public function getSupplierById(int $id)
    {
        try {
            $supplier = $this->supplierService
                ->getSupplierById($id);

            return $this->success(
                'Get supplier successfully',
                new SupplierResource($supplier)
            );
        } catch (\Exception $e) {
            $this->logError('Failed when get supplier', $e);

            return $this->error(
                'Failed when get supplier',
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    /**
     * Update supplier.
     */
    public function updateSupplier(
        int $id,
        UpdateSupplierRequest $request
    ) {
        try {
            $supplier = $this->supplierService
                ->updateSupplier(
                    $id,
                    UpdateSupplierDTO::fromRequest($request)
                );

            return $this->success(
                'Update supplier successfully',
                new SupplierResource($supplier)
            );
        } catch (\Exception $e) {
            $this->logError('Failed when update supplier', $e);

            return $this->error(
                'Failed when update supplier',
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    /**
     * Delete supplier.
     */
    public function deleteSupplier(int $id)
    {
        try {
            $this->supplierService
                ->deleteSupplier($id);

            return $this->success(
                'Delete supplier successfully',
                null,
                204
            );
        } catch (\Exception $e) {
            $this->logError('Failed when delete supplier', $e);

            return $this->error(
                'Failed when delete supplier',
                $e->getCode(),
                $e->getMessage()
            );
        }
    }
}
