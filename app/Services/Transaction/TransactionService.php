<?php

namespace App\Services\Transaction;

use App\DTOs\Transaction\StoreTransactionDTO;
use App\Enums\PaymentStatusEnum;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Repositories\Contracts\Product\StockMovementRepositoryInterface;
use App\Repositories\Contracts\Transaction\TransactionItemRepositoryInterface;
use App\Repositories\Contracts\Transaction\TransactionRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected $productRepository;
    protected $transactionRepository;
    protected $transactionItemRepository;
    protected $stockMovementRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        TransactionRepositoryInterface $transactionRepository,
        TransactionItemRepositoryInterface $transactionItemRepository,
        StockMovementRepositoryInterface $stockMovementRepository,
    ) {
        $this->productRepository = $productRepository;
        $this->transactionRepository = $transactionRepository;
        $this->transactionItemRepository = $transactionItemRepository;
        $this->stockMovementRepository = $stockMovementRepository;
    }

    public function storeTransaction(StoreTransactionDTO $dto)
    {
        $userId = auth()->id();

        $productIds = collect($dto->items)->pluck('productId')->toArray();

        $products = $this->productRepository->getProductsByIds($productIds);

        $total = 0;

        $itemsData = [];

        foreach ($dto->items as $item) {
            $product = $products[$item->productId] ?? null;

            if (!$product) {
                throw new \Exception("Product not found", 404);
            }

            if ($product->stock < $item->qty) {
                throw new \Exception("Stock not enough for {$product->name}", 422);
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
                'user_id' => $userId,
                'invoice_number' => $invoiceNumber,
                'total' => $total,
                'paid_amount' => $dto->paidAmount,
                'change_amount' => $dto->paidAmount - $total,
                'payment_method' => $dto->paymentMethod,
                'status' => PaymentStatusEnum::PAID,
            ]);

            foreach ($itemsData as $item) {
                $this->transactionItemRepository->create([
                    'user_id' => $userId,
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'qty' => $item['qty'],
                    'cost_price' => $item['cost_price'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $this->productRepository->update($item['product']->id, ['stock' => $item['product']->stock - $item['qty']]);

                $this->stockMovementRepository->create([
                    'user_id' => $userId,
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

    public function generateInvoiceNumber()
    {
        $counter = $this->transactionRepository->getLatestInvoiceByDate(Carbon::now());

        $lastNumber = $counter ? substr($counter->invoice_number, -4) : 0;

        $nextNumber = (int) $lastNumber + 1;

        return now()->format('Ymd') . 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
