<?php

namespace App\DTOs\Transaction;

class StoreTransactionDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly array $items,
        public readonly float $paidAmount,
        public readonly string $paymentMethod,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            items: array_map(
                fn($item) => TransactionItemDTO::fromArray($item),
                $request->validated('items')
            ),
            paidAmount: (float) $request->validated('paid_amount'),
            paymentMethod: (string) $request->validated('payment_method'),
        );
    }
}
