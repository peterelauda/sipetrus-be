<?php

namespace App\DTOs\Transaction;

class TransactionItemDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly int $productId,
        public readonly int $qty,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            productId: (int) $data['product_id'],
            qty: (int) $data['qty'],
        );
    }
}
