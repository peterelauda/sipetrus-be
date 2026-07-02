<?php

namespace App\Services\Transaction;

use App\DTOs\Transaction\GetTransactionDTO;
use App\DTOs\Transaction\StoreTransactionDTO;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Repositories\Contracts\Product\ProductBatchRepositoryInterface;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;
use App\Repositories\Contracts\Transaction\TransactionItemRepositoryInterface;
use App\Repositories\Contracts\Transaction\TransactionRepositoryInterface;
use App\Repositories\Contracts\Transaction\TxItemBatchRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected $productRepository;
    protected $transactionRepository;
    protected $transactionItemRepository;
    protected $stockMovementRepository;
    protected $productBatchRepository;
    protected $transactionItemBatchRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        TransactionRepositoryInterface $transactionRepository,
        TransactionItemRepositoryInterface $transactionItemRepository,
        StockMovementRepositoryInterface $stockMovementRepository,
        ProductBatchRepositoryInterface $productBatchRepository,
        TxItemBatchRepositoryInterface $transactionItemBatchRepository,
    ) {
        $this->productRepository = $productRepository;
        $this->transactionRepository = $transactionRepository;
        $this->transactionItemRepository = $transactionItemRepository;
        $this->stockMovementRepository = $stockMovementRepository;
        $this->productBatchRepository = $productBatchRepository;
        $this->transactionItemBatchRepository = $transactionItemBatchRepository;
    }

    public function getTransactions(GetTransactionDTO $dto)
    {
        return $this->transactionRepository
            ->getTransactions(
                $dto->page,
                $dto->limit,
                Carbon::parse($dto->startDate),
                Carbon::parse($dto->endDate),
                PaymentMethodEnum::from($dto->paymentMethod)
            );
    }

    public function getTransactionById(string $id)
    {
        $storeId = auth()->user()->store_id;

        $transaction = $this->transactionRepository
            ->getTransactionDetail($id);

        if (!$transaction || $transaction->store_id !== $storeId) {
            throw new \Exception('Invalid transaction ID');
        }

        return $transaction;
    }

    public function storeTransaction(StoreTransactionDTO $dto)
    {
        $storeId = auth()->user()->store_id;

        $productIds = collect($dto->items)->pluck('productId')->toArray();

        $products = $this->productRepository->getProductsByIds($productIds);

        $total = 0;

        $itemsData = [];

        foreach ($dto->items as $item) {
            $product = $products[$item->productId] ?? null;

            if (!$product) {
                throw new \Exception("Product not found", 404);
            }

            $batches = $this->productBatchRepository
                ->getAvailableBatchesByProduct(
                    $product->id
                );

            $availableStock = $batches->sum('stock');

            if ($availableStock < $item->qty) {
                throw new \Exception(
                    "Stock not enough for {$product->name}",
                    422
                );
            }

            $subtotal = $product->price * $item->qty;

            $total += $subtotal;

            $itemsData[] = [
                'product' => $product,
                'qty' => $item->qty,
                'price' => $product->price,
                'cost_price' => $product->cost_price,
                'subtotal' => $subtotal,
            ];
        }

        if ($dto->paidAmount < $total) {
            throw new \Exception('Paid amount is less than total', 422);
        }

        $invoiceNumber = $this->generateInvoiceNumber();

        DB::beginTransaction();

        try {
            $transaction = $this->transactionRepository->create([
                'store_id' => $storeId,
                'invoice_number' => $invoiceNumber,
                'total' => $total,
                'paid_amount' => $dto->paidAmount,
                'change_amount' => $dto->paidAmount - $total,
                'payment_method' => $dto->paymentMethod,
                'status' => PaymentStatusEnum::PAID,
            ]);

            foreach ($itemsData as $item) {
                $transactionItem = $this->transactionItemRepository->create([
                    'store_id' => $storeId,
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'qty' => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Reduce stock from product batches

                $remainingQty = $item['qty'];

                $batches = $this->productBatchRepository
                    ->getAvailableBatchesByProduct(
                        $item['product']->id
                    );

                foreach ($batches as $batch) {

                    if ($remainingQty <= 0) {
                        break;
                    }

                    $deductQty = min(
                        $batch->stock,
                        $remainingQty
                    );

                    $this->transactionItemBatchRepository->create([
                        'transaction_item_id' => $transactionItem->id,
                        'product_batch_id' => $batch->id,
                        'qty' => $deductQty,
                    ]);

                    $this->productBatchRepository->update(
                        $batch->id,
                        [
                            'stock' => $batch->stock - $deductQty
                        ]
                    );

                    $remainingQty -= $deductQty;
                }

                $this->productRepository->update($item['product']->id, ['stock' => $item['product']->stock - $item['qty']]);

                $this->stockMovementRepository->create([
                    'store_id' => $storeId,
                    'product_id' => $item['product']->id,
                    'type' => 'out',
                    'qty' => $item['qty'],
                    'reference' => $invoiceNumber,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage(), 500);
        }

        return $transaction;
    }

    public function cancelTransactionById(string $id)
    {
        $storeId = auth()->user()->store_id;

        $transaction = $this->transactionRepository
            ->getTransactionById(
                $id,
                $storeId
            );

        if (!$transaction) {
            throw new \Exception(
                'Transaction not found'
            );
        }

        if (
            $transaction->status ===
            PaymentStatusEnum::CANCELLED
        ) {
            throw new \Exception(
                'Transaction already cancelled'
            );
        }

        DB::beginTransaction();

        try {
            foreach ($transaction->items as $item) {
                $product = $this->productRepository
                    ->getById(
                        $item->product_id
                    );

                // Refund product stock
                $this->productRepository->update(
                    $item->product_id,
                    [
                        'stock' =>
                            $product->stock +
                            $item->qty
                    ]
                );

                // Refund stock in product batches
                foreach ($item->batchAllocations as $allocation) {
                    $batch = $this->productBatchRepository
                        ->getById(
                            $allocation->product_batch_id
                        );

                    $this->productBatchRepository
                        ->update(
                            $batch->id,
                            [
                                'stock' =>
                                    $batch->stock +
                                    $allocation->qty
                            ]
                        );
                }

                $this->stockMovementRepository
                    ->create([
                        'store_id' => $storeId,
                        'product_id' => $item->product_id,
                        'type' => 'in',
                        'qty' => $item->qty,
                        'reference' =>
                            'CANCEL-' .
                            $transaction->invoice_number,
                    ]);
            }

            $this->transactionRepository
                ->cancelTransactionById(
                    $id
                );

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function generateInvoiceNumber()
    {
        $counter = $this->transactionRepository->getLatestInvoiceByDate(Carbon::now());

        $lastNumber = $counter ? substr($counter->invoice_number, -4) : 0;

        $nextNumber = (int) $lastNumber + 1;

        return now()->format('Ymd') . 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
