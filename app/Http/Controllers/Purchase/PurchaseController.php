<?php

namespace App\Http\Controllers\Purchase;

use App\DTOs\Purchase\CreatePurchaseDTO;
use App\DTOs\Purchase\GetPurchasesDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\CreatePurchaseRequest;
use App\Http\Requests\Purchase\GetPurchasesRequest;
use App\Http\Resources\Purchase\PurchaseDetailResource;
use App\Http\Resources\Purchase\PurchaseResource;
use App\Services\Purchase\PurchaseService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class PurchaseController extends Controller
{
    use ApiLogger, ApiResponser;

    public function __construct(
        private PurchaseService $purchaseService
    ) {
    }

    /**
     * Create Purchase
     */
    public function createPurchase(
        CreatePurchaseRequest $request
    ) {
        try {
            $purchase = $this->purchaseService
                ->createPurchase(
                    CreatePurchaseDTO::fromRequest($request)
                );

            return $this->success(
                'Create purchase successfully',
                new PurchaseDetailResource($purchase),
                201
            );
        } catch (\Exception $e) {
            $this->logError(
                'Failed when create purchase',
                $e
            );

            return $this->error(
                'Failed when create purchase',
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    /**
     * Get Purchases
     */
    public function getPurchases(
        GetPurchasesRequest $request
    ) {
        try {
            $purchases = $this->purchaseService
                ->getPurchases(
                    GetPurchasesDTO::fromRequest($request)
                );

            return $this->success(
                'Get purchases successfully',
                PurchaseResource::collection($purchases)
            );
        } catch (\Exception $e) {
            $this->logError(
                'Failed when get purchases',
                $e
            );

            return $this->error(
                'Failed when get purchases',
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    /**
     * Get Purchase Detail
     */
    public function getPurchaseById(
        int $id
    ) {
        try {
            $purchase = $this->purchaseService
                ->getPurchaseById($id);

            return $this->success(
                'Get purchase successfully',
                new PurchaseDetailResource($purchase)
            );
        } catch (\Exception $e) {
            $this->logError(
                'Failed when get purchase',
                $e
            );

            return $this->error(
                'Failed when get purchase',
                $e->getCode(),
                $e->getMessage()
            );
        }
    }
}
