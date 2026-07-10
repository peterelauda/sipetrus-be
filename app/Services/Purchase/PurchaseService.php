<?php

namespace App\Services\Purchase;

use App\DTOs\Purchase\CreatePurchaseDTO;
use App\DTOs\Purchase\GetPurchasesDTO;
use App\Repositories\Contracts\Product\ProductBatchRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;
use App\Repositories\Contracts\Purchase\PurchaseItemRepositoryInterface;
use App\Repositories\Contracts\Purchase\PurchaseRepositoryInterface;
use App\Services\Product\ProductBatchService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ProductBatchRepositoryInterface $productBatchRepository,
        private ProductBatchService $productBatchService,
        private StockMovementRepositoryInterface $stockMovementRepository,
        private PurchaseRepositoryInterface $purchaseRepository,
        private PurchaseItemRepositoryInterface $purchaseItemRepository
    ) {
        //
    }

    public function getPurchases(GetPurchasesDTO $dto)
    {
        return $this->purchaseRepository
            ->getPurchases($dto);
    }

    public function getPurchaseById(int $id)
    {
        $purchase = $this->purchaseRepository
            ->getById($id);

        if (!$purchase) {
            throw new \Exception(
                'Purchase not found.',
                404
            );
        }

        if ($purchase->store_id !== auth()->user()->store_id) {
            throw new \Exception(
                'Purchase not found.',
                404
            );
        }

        return $purchase->load([
            'supplier',
            'items.product',
        ]);
    }

    public function createPurchase(CreatePurchaseDTO $dto)
    {
        $storeId = auth()->user()->store_id;

        $productIds = collect($dto->items)
            ->pluck('productId')
            ->toArray();

        $products = $this->productRepository
            ->getProductsByIds($productIds);

        $totalCost = 0;

        $itemsData = [];

        foreach ($dto->items as $item) {
            $product = $products[$item->productId] ?? null;

            if (!$product) {
                throw new \Exception(
                    "Product not found.",
                    404
                );
            }

            $subtotal = $item->qty * $item->costPrice;

            $totalCost += $subtotal;

            $itemsData[] = [
                'product' => $product,
                'qty' => $item->qty,
                'cost_price' => $item->costPrice,
                'subtotal' => $subtotal,
                'expired_date' => $item->expiredDate,
            ];
        }

        $invoiceNumber = $this->generatePurchaseInvoiceNumber();

        DB::beginTransaction();

        try {
            /**
             * Create Purchase
             */
            $purchase = $this->purchaseRepository->create([
                'store_id' => $storeId,
                'supplier_id' => $dto->supplierId,
                'invoice_number' => $invoiceNumber,
                'purchase_date' => $dto->purchaseDate,
                'total_cost' => $totalCost,
            ]);

            /**
             * Create Purchase Items
             */
            foreach ($itemsData as $item) {
                $batchNumber = $this->productBatchService->generateBatchNumber(
                    $item['product']->id
                );

                $this->purchaseItemRepository->create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product']->id,
                    'batch_number' => $batchNumber,
                    'qty' => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $item['subtotal'],
                    'expired_date' => $item['expired_date'],
                ]);

                /**
                 * Create Product Batch
                 */
                $this->productBatchRepository->create([
                    'product_id' => $item['product']->id,
                    'batch_number' => $batchNumber,
                    'stock' => $item['qty'],
                    'expired_date' => $item['expired_date'],
                ]);

                /**
                 * Update Product Stock
                 */
                $this->productRepository->update(
                    $item['product']->id,
                    [
                        'stock' =>
                        $item['product']->stock +
                            $item['qty'],

                        'cost_price' =>
                        $item['cost_price'],
                    ]
                );

                /**
                 * Stock Movement
                 */
                $this->stockMovementRepository->create([
                    'store_id' => $storeId,
                    'product_id' => $item['product']->id,
                    'type' => 'in',
                    'qty' => $item['qty'],
                    'reference' => $invoiceNumber,
                ]);
            }

            DB::commit();

            return $purchase->load([
                'supplier',
                'items.product',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception(
                $e->getMessage(),
                500
            );
        }
    }

    public function generatePurchaseInvoiceNumber()
    {
        $counter = $this->purchaseRepository
            ->getLatestInvoiceByDate(Carbon::now());

        $lastNumber = $counter
            ? substr($counter->invoice_number, -4)
            : 0;

        $nextNumber = (int) $lastNumber + 1;

        return now()->format('Ymd')
            . 'PUR-'
            . str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
    }
}
